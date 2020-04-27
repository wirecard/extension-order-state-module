<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Application\Service;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;

/**
 * Class OrderStateTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Application\Service
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Application\Service\OrderState
 * @since 1.0.0
 */
class OrderStateTest extends Unit
{
    const EXTERNAL_ORDER_STATE_AUTHORIZED = "external_authorized";
    const EXTERNAL_ORDER_STATE_STARTED = "external_started";
    const EXTERNAL_ORDER_STATE_PENDING = "external_pending";
    const EXTERNAL_ORDER_STATE_PROCESSING = "external_processing";
    const EXTERNAL_ORDER_STATE_FAILED = "external_failed";

    /**
     * @var MappingDefinition
     */
    private $mapDefinition;

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
        $this->mapDefinition = \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => Expected::atLeastOnce($this->getSampleMapper())
        ]);
        $this->orderState = new OrderState(new GenericOrderStateMapper($this->mapDefinition));
    }

    /**
     * @return array
     */
    private function getSampleMapper()
    {
        return [
            self::EXTERNAL_ORDER_STATE_STARTED => Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING => Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_FAILED => Constant::ORDER_STATE_FAILED,
            self::EXTERNAL_ORDER_STATE_AUTHORIZED => Constant::ORDER_STATE_AUTHORIZED,
            self::EXTERNAL_ORDER_STATE_PROCESSING => Constant::ORDER_STATE_PROCESSING,
        ];
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
    public function inputDtoDataProvider()
    {
        yield "debit_started_success_initial_return_pending" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            self::EXTERNAL_ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING
        ];

        yield "debit_started_failure_initial_return_failed" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_DEBIT,
            self::EXTERNAL_ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_FAILED
        ];

        yield "debit_started_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            self::EXTERNAL_ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield "purchase_pending_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            self::EXTERNAL_ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield "authorization_pending_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZE,
            self::EXTERNAL_ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_AUTHORIZED
        ];
    }

    /**
     * @return \Generator
     */
    public function inputDTOExceptionDataProvider()
    {
        $initialReturnAllNotPermittedStates = array_diff(
            array_keys($this->getSampleMapper()),
            [
                self::EXTERNAL_ORDER_STATE_STARTED,
                self::EXTERNAL_ORDER_STATE_PENDING,
                self::EXTERNAL_ORDER_STATE_FAILED
            ]
        );
        foreach ($initialReturnAllNotPermittedStates as $orderState) {
            foreach (Constant::getTransactionTypes() as $transactionType) {
                yield "{$transactionType}_{$orderState}_success_initial_return_ignorable_exception" => [
                    Constant::PROCESS_TYPE_INITIAL_RETURN,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    Constant::TRANSACTION_TYPE_AUTHORIZE,
                    $orderState,
                    IgnorableStateException::class
                ];
            }
        }

        yield "invalid_argument_exception_invalid_process" => [
            "INVALID_PROCESS_TYPE",
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZE,
            Constant::ORDER_STATE_STARTED,
            OrderStateInvalidArgumentException::class
        ];

        yield "invalid_argument_exception_invalid_transaction_state" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            "INVALID_TRANSACTION_STATE",
            Constant::TRANSACTION_TYPE_AUTHORIZE,
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
            Constant::TRANSACTION_TYPE_AUTHORIZE,
            "INVALID CURRENT_ORDER_STATE",
            OrderStateInvalidArgumentException::class
        ];
    }

    /**
     * @group        integration
     * @small
     * @covers       ::process
     * @dataProvider inputDtoDataProvider
     *
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @param string $expectedState
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Exception
     */
    public function testProcess($processType, $transactionState, $transactionType, $currentOrderState, $expectedState)
    {
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getProcessType' => Expected::once($processType),
                'getTransactionState' => Expected::once($transactionState),
                'getTransactionType' => Expected::once($transactionType),
                'getCurrentOrderState' => Expected::once($currentOrderState)
            ]
        );

        $this->assertEquals($expectedState, $this->orderState->process($inputDTO));
    }

    /**
     * @group        integration
     * @small
     * @covers       ::process
     * @dataProvider inputDTOExceptionDataProvider
     *
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @param string $exception
     * @throws IgnorableStateException
     * @throws OrderStateInvalidArgumentException
     * @throws \Exception
     */
    public function testProcessException(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $exception
    ) {
        $this->expectException($exception);
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getProcessType' => Expected::once($processType),
                'getTransactionState' => Expected::once($transactionState),
                'getTransactionType' => Expected::once($transactionType),
                'getCurrentOrderState' => Expected::once($currentOrderState)
            ]
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
        $mapping = array_merge($this->getSampleMapper(), ['X' => 'INVALID_ORDER_STATE_TYPE']);
        $this->mapDefinition = \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => $mapping
        ]);
        new OrderState(new GenericOrderStateMapper($this->mapDefinition));
    }
}
