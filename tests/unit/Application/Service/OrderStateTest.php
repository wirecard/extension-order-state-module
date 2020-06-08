<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Application\Service;

use Codeception\Test\Unit;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class OrderStateTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Application\Service
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Application\Service\OrderState
 * @since 1.0.0
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderStateTest extends Unit
{
    use MockCreator;

    /**
     * @var OrderStateMapper
     */
    private $mapper;

    /**
     * @var OrderState
     */
    private $orderState;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        $this->mapper = $this->createGenericMapper();
        $this->orderState = new OrderState($this->mapper, $this->getDefaultPrecision());
    }

    /**
     * GRAMMAR: {TransactionType}_{CurrentOrderState}_{TransactionState}_{ProcessType}_{NextOrderState}
     *
     * TransactionType: Payment type,
     * CurrentOrderState: Current order state,
     * TransactionState: Transaction state,
     * ProcessType: Process type,
     * NextOrderState: Next order state
     *
     * Example
     * debit_started_success_initial_return_pending
     *
     * Explanation:
     * Order is currently in state{CurrentOrderState} "started" and was paid with Debit(TransactionType)
     * Transaction{TransactionState} request to gateway was successful.
     * Response came during initial return / notification
     * Next order state should be: Pending
     *
     * @return \Generator
     */
    public function inputDtoInitialDataProvider()
    {
        yield "debit_started_success_initial_return_pending" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            Constant::ORDER_STATE_PENDING
        ];

        yield "debit_started_failure_initial_return_failed" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            Constant::ORDER_STATE_FAILED
        ];

        yield "debit_started_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            Constant::ORDER_STATE_PROCESSING
        ];

        yield "purchase_pending_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PENDING,
            Constant::ORDER_STATE_PROCESSING
        ];

        yield "authorization_pending_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_PENDING,
            Constant::ORDER_STATE_AUTHORIZED
        ];
    }

    /**
     * @return \Generator
     */
    public function inputDTOExceptionInitialDataProvider()
    {
        $initialReturnAllNotPermittedStates = array_diff(Constant::getOrderStates(), [
            Constant::ORDER_STATE_STARTED,
            Constant::ORDER_STATE_PENDING,
            Constant::ORDER_STATE_FAILED
        ]);
        foreach ($initialReturnAllNotPermittedStates as $orderState) {
            foreach (Constant::getTransactionTypes() as $transactionType) {
                yield "{$transactionType}_{$orderState}_success_initial_return_ignorable_exception" => [
                    Constant::PROCESS_TYPE_INITIAL_RETURN,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    Constant::TRANSACTION_TYPE_AUTHORIZATION,
                    $orderState,
                    IgnorableStateException::class
                ];
            }
        }

        yield "invalid_argument_exception_invalid_process" => [
            "INVALID_PROCESS_TYPE",
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_STARTED,
            OrderStateInvalidArgumentException::class
        ];

        yield "invalid_argument_exception_invalid_transaction_state" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            "INVALID_TRANSACTION_STATE",
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_STARTED,
            OrderStateInvalidArgumentException::class
        ];

        yield "invalid_argument_exception_invalid_transaction_type" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            "INVALID TRANSACTION TYPE",
            Constant::ORDER_STATE_STARTED,
            OrderStateInvalidArgumentException::class
        ];

        yield "invalid_argument_exception_invalid_order_state" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            "INVALID CURRENT_ORDER_STATE",
            OrderStateInvalidArgumentException::class
        ];
    }

    /**
     * @group        integration
     * @small
     * @covers       ::process
     * @dataProvider inputDtoInitialDataProvider
     *
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @param string $expectedInternalState
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
     * @throws \Exception
     */
    public function testInitialPaymentProcess(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $expectedInternalState
    ) {

        $inputDTO = $this->createDummyInputDTO(
            $processType,
            $transactionState,
            $transactionType,
            $this->mapper->toExternal($this->fromOrderStateRegistry($currentOrderState))
        );

        $expectedState = $this->mapper->toExternal($this->fromOrderStateRegistry($expectedInternalState));
        $this->assertEquals($expectedState, $this->orderState->process($inputDTO));
    }

    /**
     * @group        integration
     * @small
     * @covers       ::process
     * @dataProvider inputDTOExceptionInitialDataProvider
     *
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @param $exception
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
     * @throws \Exception
     */
    public function testInitialProcessException(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $exception
    ) {
        $this->expectException($exception);

        $inputDTO = $this->createDummyInputDTO(
            $processType,
            $transactionState,
            $transactionType,
            $this->mapper->toExternal($this->fromOrderStateRegistry($currentOrderState))
        );

        $this->orderState->process($inputDTO);
    }

    /**
     * @group  integration
     * @small
     * @covers ::__construct
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function testFailingConstructor()
    {
        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        new OrderState($this->createGenericMapper(['X' => 'INVALID_ORDER_STATE_TYPE']), $this->getDefaultPrecision());
        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidArgumentException::class);
        new OrderState($this->createGenericMapper(), 0);
    }

    /**
     * @return \Generator
     */
    public function inputDtoPostProcessingRefundOperationDataProvider()
    {
        // Check refund scenario
        $scenario = [
            // Open amount: 100; Requested amount: 30; State: partial refunded
            "step1" => [100, 30, 0, 0, Constant::ORDER_STATE_PARTIAL_REFUNDED],
            // Open amount: 70; Requested amount: 40; State: partial refunded
            "step2" => [100, 40, 0, 30, Constant::ORDER_STATE_PARTIAL_REFUNDED],
            // Open amount: 30; Requested amount: 30; State: refunded
            "step3" => [100, 30, 0, 70, Constant::ORDER_STATE_REFUNDED],
        ];

        $refundableTransactionTypeList = [
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_DEBIT,
            Constant::TRANSACTION_TYPE_CREDIT,
        ];

        foreach ($refundableTransactionTypeList as $type) {
            foreach ($scenario as $step => $stepData) {
                list($orderAmount, $requestedAmount, $capturedAmount, $refundedAmount, $nextState) = $stepData;
                yield "{$step}_{$type}_processing_success_pp_notification_{$nextState}" => [
                    Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    $type,
                    Constant::ORDER_STATE_PROCESSING,
                    $orderAmount,
                    $requestedAmount,
                    $capturedAmount,
                    $refundedAmount,
                    $nextState,
                ];
            }
        }
    }

    /**
     * @group integration
     * @small
     * @dataProvider inputDtoPostProcessingRefundOperationDataProvider
     * @covers ::process
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param mixed|int $currentOrderState
     * @param float $orderTotalAmount
     * @param float $requestedAmount
     * @param float $orderCapturedAmount
     * @param float $orderRefundedAmount
     * @param string $expectedInternalState
     * @throws IgnorablePostProcessingFailureException
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Exception
     */
    public function testRefundOperationsOnPostProcessingPaymentProcess(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $orderTotalAmount,
        $requestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount,
        $expectedInternalState
    ) {
        $inputDTO = $this->createDummyInputPostProcessingDTO(
            $processType,
            $transactionState,
            $transactionType,
            $this->mapper->toExternal($this->fromOrderStateRegistry($currentOrderState)),
            $orderTotalAmount,
            $requestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );
        $expectedState = $this->mapper->toExternal($this->fromOrderStateRegistry($expectedInternalState));
        $this->assertEquals($expectedState, $this->orderState->process($inputDTO));
    }

    /**
     * @return \Generator
     */
    public function inputDTOExceptionPostProcessingDataProvider()
    {
        yield "void-purchase_processing_success_pp_return_processing" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::ORDER_STATE_PROCESSING,
            100,
            100,
            0,
            0,
            IgnorableStateException::class
        ];

        yield "refund-purchase_processing_failed_pp_return_processing" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
            Constant::ORDER_STATE_PROCESSING,
            100,
            100,
            0,
            0,
            IgnorableStateException::class
        ];

        foreach (Constant::getOrderStates() as $orderState) {
            foreach (Constant::getTransactionTypes() as $transactionType) {
                yield "{$transactionType}_{$orderState}_success_pp_return_ignorable_exception" => [
                    Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
                    Constant::TRANSACTION_STATE_FAILED,
                    $transactionType,
                    $orderState,
                    100,
                    100,
                    0,
                    0,
                    IgnorablePostProcessingFailureException::class
                ];

                yield "{$transactionType}_{$orderState}_success_pp_notification_ignorable_exception" => [
                    Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
                    Constant::TRANSACTION_STATE_FAILED,
                    $transactionType,
                    $orderState,
                    100,
                    100,
                    0,
                    0,
                    IgnorablePostProcessingFailureException::class
                ];
            }
        }

        yield "pp_return_request_order_total_less_request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PROCESSING,
            10,
            100,
            0,
            0,
            InvalidPostProcessDataException::class
        ];

        yield  "pp_return_request_amount_invalid" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PROCESSING,
            100,
            0,
            0,
            0,
            InvalidPostProcessDataException::class
        ];

        yield  "pp_return_order_total_amount_invalid" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PROCESSING,
            0,
            100,
            0,
            0,
            InvalidPostProcessDataException::class
        ];
        yield  "pp_return_order_refund_amount_greater_than_capture_amount" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100,
            100,
            20,
            1000,
            InvalidPostProcessDataException::class
        ];
    }

    /**
     * @group integration
     * @small
     * @dataProvider inputDTOExceptionPostProcessingDataProvider
     * @covers ::process
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param mixed|int $currentOrderState
     * @param float $orderTotalAmount
     * @param float $requestedAmount
     * @param float $orderCapturedAmount
     * @param float $orderRefundedAmount
     * @param $exception
     * @throws IgnorablePostProcessingFailureException
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Exception
     */
    public function testPostProcessingProcessException(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $orderTotalAmount,
        $requestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount,
        $exception
    ) {
        $this->expectException($exception);
        $inputDTO = $this->createDummyInputPostProcessingDTO(
            $processType,
            $transactionState,
            $transactionType,
            $this->mapper->toExternal($this->fromOrderStateRegistry($currentOrderState)),
            $orderTotalAmount,
            $requestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );

        $this->orderState->process($inputDTO);
    }

    /**
     * @return \Generator
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function realFullScenariosDataProvider()
    {
        yield "Initial Return. Failed payment. Transaction wasn't successful" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_STARTED,
            0, 0, 0, 0,
            Constant::ORDER_STATE_FAILED,
            null
        ];

        yield "Initial Return. Failed payment. Order is invalid created or broken ..." => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_FAILED,
            0, 0, 0, 0,
            Constant::ORDER_STATE_FAILED,
            null
        ];

        yield "Initial Return. Do initial payment" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_STARTED,
            0, 0, 0, 0,
            Constant::ORDER_STATE_PENDING,
            null
        ];

        yield "Initial Notification. Failed verifying payment was successful" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_STARTED,
            0, 0, 0, 0,
            Constant::ORDER_STATE_FAILED,
            null
        ];

        yield "Initial Notification. Verify payment" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
            Constant::ORDER_STATE_PENDING,
            0, 0, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            null
        ];
        // Scenario I
        yield "Post Processing Return. Void capture" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 100, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            IgnorableStateException::class
        ];

        yield "Post Processing Return. Void capture. Failed Transaction" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 100, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            IgnorablePostProcessingFailureException::class
        ];

        yield "Post Processing Notification. Void capture" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 100, 0, 0,
            Constant::ORDER_STATE_CANCELED,
            null
        ];

        yield "Post Processing Notification. Void capture. Failed transaction" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 100, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            IgnorablePostProcessingFailureException::class
        ];

        // Scenario II
        yield "Post Processing Return. Full Capture. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PENDING,
            100, 100, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Authorization is captured." => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 100, 0, 0,
            Constant::ORDER_STATE_PROCESSING,
            null
        ];
        // Scenario III
        yield "Post Processing Return. Full refund. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PROCESSING,
            100, 100, 0, 0,
            Constant::ORDER_STATE_PROCESSING,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Full amount refunded" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PROCESSING,
            100, 100, 0, 0,
            Constant::ORDER_STATE_REFUNDED,
            null
        ];
        // Scenario IV
        yield "Post Processing Return. Partial refund I. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PROCESSING,
            100, 100, 0, 0,
            Constant::ORDER_STATE_PROCESSING,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Partial refund I" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PROCESSING,
            100, 20, 0, 0,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            null
        ];
        yield "Post Processing Return. Partial refund II. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            100, 80, 0, 20,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Partial refund. Refunded amount is full." => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            100, 80, 0, 20,
            Constant::ORDER_STATE_REFUNDED,
            null
        ];
        // Scenario IV
        yield "Post Processing Return. Partial capture I. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 30, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Partial capture I" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 30, 0, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            null
        ];
        yield "Post Processing Return. Partial capture II. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 40, 30, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Partial capture II" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 40, 30, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            null
        ];
        yield "Post Processing Return. Partial capture III. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 30, 70, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            IgnorableStateException::class
        ];
        yield "Post Processing Notification. Partial capture III. Amount is full captured." => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 30, 70, 0,
            Constant::ORDER_STATE_PROCESSING,
            null
        ];
        // Scenario VI
        yield "1.0) Post Processing Return. Partial capture. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 30, 0, 0,
            Constant::ORDER_STATE_AUTHORIZED,
            IgnorableStateException::class
        ];
        yield "1.0) Post Processing Notification. Partial capture" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_AUTHORIZED,
            100, 30, 0, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            null
        ];
        yield "1.1) Post Processing Return. Partial refund. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 20, 30, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            IgnorableStateException::class
        ];
        yield "1.1) Post Processing Notification. Partial refund" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 20, 30, 0,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            null
        ];
        yield "1.2) Post Processing Return. Partial refund. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 10, 30, 20,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            IgnorableStateException::class
        ];
        yield "1.2) Post Processing Notification. Partial refund" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 10, 30, 20,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            null
        ];
        yield "1.3) Post Processing Return. Partial capture. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            100, 70, 30, 30,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            IgnorableStateException::class
        ];
        yield "1.3) Post Processing Notification. Partial capture." => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            100, 70, 30, 30,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            null
        ];
        yield "1.4) Post Processing Return. Partial refund. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 10, 100, 30,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            IgnorableStateException::class
        ];
        yield "1.4) Post Processing Notification. Partial refund." => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            100, 10, 100, 30,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            null
        ];
        yield "1.5) Post Processing Return. Partial refund. Ignore request" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            100, 60, 100, 40,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            IgnorableStateException::class
        ];
        yield "1.5) Post Processing Notification. Partial refund." => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_REFUNDED,
            100, 60, 100, 40,
            Constant::ORDER_STATE_REFUNDED,
            null
        ];
    }


    /**
     * @group integration
     * @small
     * @dataProvider realFullScenariosDataProvider
     * @covers ::process
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param mixed|int $currentOrderState
     * @param float $orderTotalAmount
     * @param float $requestedAmount
     * @param float $orderCapturedAmount
     * @param float $orderRefundedAmount
     * @param string $expectedInternalOrderState
     * @param $exception
     * @throws IgnorablePostProcessingFailureException
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function testRealFullScenarios(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $orderTotalAmount,
        $requestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount,
        $expectedInternalOrderState,
        $exception
    ) {
        if (null !== $exception) {
            $this->expectException($exception);
        }
        $inputDTO = $this->createDummyInputPostProcessingDTO(
            $processType,
            $transactionState,
            $transactionType,
            $this->mapper->toExternal($this->fromOrderStateRegistry($currentOrderState)),
            $orderTotalAmount,
            $requestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );

        $expectedState = $this->mapper->toExternal($this->fromOrderStateRegistry($expectedInternalOrderState));
        $this->assertEquals($expectedState, $this->orderState->process($inputDTO));
    }


    /**
     * @return \Generator
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function floatRoundingDataProvider()
    {
        yield "Rounding test for Refunded context" => [
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            22.94, 10.94, 22.94, 12,
            Constant::ORDER_STATE_REFUNDED
        ];

        yield "Rounding test for Processing context" => [
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            22.94, 10.94, 12, 0,
            Constant::ORDER_STATE_PROCESSING
        ];

        $numberOne = 10.94;
        $numberTwo = 12;

        yield "Rounding test for Partial refunded context" => [
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            // $x + $y -> reproduce calculating at runtime causes rounding errors
            22.94, 10.94, $numberOne + $numberTwo, 3.5,
            Constant::ORDER_STATE_PARTIAL_REFUNDED
        ];

        $numberOne = 10.1;
        $numberTwo = 0.2;
        yield "Rounding test for Partial refunded context 2" => [
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::ORDER_STATE_PARTIAL_CAPTURED,
            // $x + $y -> reproduce calculating at runtime causes rounding errors
            22.94,
            $numberOne + $numberTwo,
            12.3,
            2,
            Constant::ORDER_STATE_PARTIAL_REFUNDED
        ];

    }

        /**
     * @group integration
     * @small
     * @dataProvider floatRoundingDataProvider
     * @covers ::process
     * @param string $transactionType
     * @param mixed|int $currentOrderState
     * @param float $orderTotalAmount
     * @param float $requestedAmount
     * @param float $orderCapturedAmount
     * @param float $orderRefundedAmount
     * @param string $expectedInternalOrderState
     * @throws IgnorablePostProcessingFailureException
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function testFloatRounding(
        $transactionType,
        $currentOrderState,
        $orderTotalAmount,
        $requestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount,
        $expectedInternalOrderState
    ) {
        $inputDTO = $this->createDummyInputPostProcessingDTO(
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            $transactionType,
            $this->mapper->toExternal($this->fromOrderStateRegistry($currentOrderState)),
            $orderTotalAmount,
            $requestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );

        $expectedState = $this->mapper->toExternal($this->fromOrderStateRegistry($expectedInternalOrderState));
        $this->assertEquals($expectedState, $this->orderState->process($inputDTO));
    }
}
