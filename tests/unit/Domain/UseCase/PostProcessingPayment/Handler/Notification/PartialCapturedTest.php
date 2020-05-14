<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler\Notification;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\PartialCaptured;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class PartialCapturedTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\PartialCaptured
 * @since 1.0.0
 */
class PartialCapturedTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var PartialCaptured
     */
    protected $handler;

    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData
     */
    private $postProcessData;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _setUp()
    {
        $this->postProcessData = $this->createPostProcessData(
            Constant::ORDER_STATE_PROCESSING,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            40,
            0,
            0
        );
        $this->handler = new PartialCaptured($this->postProcessData);
    }

    /**
     * @group unit
     * @small
     */
    public function testDefinition()
    {
        $this->assertInstanceOf(NotificationHandler::class, $this->handler);
    }

    /**
     * @return \Generator
     */
    public function ignorableScenariosDataProvider()
    {
        $capturingTypes = [
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::TRANSACTION_TYPE_VOID_CAPTURE,
        ];
        $noneRefundableTypes = array_diff(Constant::getTransactionTypes(), $capturingTypes);
        foreach (Constant::getOrderStates() as $orderState) {
            foreach ($noneRefundableTypes as $noneRefundableType) {
                yield "none_capturing_{$noneRefundableType}_{$orderState}_on_partial_capture_scope" => [
                    $orderState,
                    $noneRefundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    30,
                    0,
                    0
                ];
            }

            yield "capture_is_full_on_entry_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                100,
                0,
                0
            ];

            yield "full_captured_never_refunded_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                70,
                30,
                0
            ];

            yield "capture_is_already_full_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                100,
                100,
                0
            ];

            yield "refund_not_greater_as_capture_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                20,
                30,
                10
            ];
        }
    }


    /**
     * @group unit
     * @small
     * @dataProvider ignorableScenariosDataProvider
     * @covers ::calculate
     * @covers ::onCaptureTransactionRequest
     * @covers ::onRefundTransactionRequest
     * @covers ::isCaptureAmountOverRefundAmount
     * @covers ::getCalculatedCaptureTotalAmount
     * @covers ::isNotFullCapturedAmount
     * @covers ::isFullCapturedAndPartialRefunded
     * @covers ::isCaptureAmountOverRefundAmountOnRefundContext
     * @covers ::isNotFullCapturedAmountOnRefundContext
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @param float $orderTotalAmount
     * @param float $requestedAmount
     * @param float $orderCapturedAmount
     * @param float $orderRefundedAmount
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateResultIgnorable(
        $orderState,
        $transactionType,
        $transactionState,
        $orderTotalAmount,
        $requestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount
    ) {
        $this->postProcessData = $this->createPostProcessData(
            $orderState,
            $transactionType,
            $transactionState,
            $orderTotalAmount,
            $requestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );
        $handler = new PartialCaptured($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals(null, $result);
    }

    /**
     * @return \Generator
     */
    public function nextStateCasesDataProvider()
    {
        foreach (Constant::getOrderStates() as $orderState) {
            yield "not_full_capture_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                30,
                66.999,
                0
            ];

            yield "full_capture_partial_refunded_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                30,
                70,
                10
            ];

            yield "capture_over_refund_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                30,
                30,
                30
            ];

            yield "refund_equals_0_capture-authorization_{$orderState}_on_partial_capture_scope" => [
                $orderState,
                Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
                Constant::TRANSACTION_STATE_SUCCESS,
                100,
                30,
                20,
                0
            ];

            $refundableTransactionStates = [
                Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
                Constant::TRANSACTION_TYPE_VOID_CAPTURE,
            ];
            foreach ($refundableTransactionStates as $refundableTransactionState) {
                yield "capture_over_refund_{$refundableTransactionState}_{$orderState}_on_partial_capture_scope" => [
                    $orderState,
                    $refundableTransactionState,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    30,
                    91,
                    60
                ];
            }
        }
    }

    /**
     * @group unit
     * @small
     * @dataProvider nextStateCasesDataProvider
     * @covers ::calculate
     * @covers ::onCaptureTransactionRequest
     * @covers ::onRefundTransactionRequest
     * @covers ::isCaptureAmountOverRefundAmount
     * @covers ::getCalculatedCaptureTotalAmount
     * @covers ::isNotFullCapturedAmount
     * @covers ::isFullCapturedAndPartialRefunded
     * @covers ::isCaptureAmountOverRefundAmountOnRefundContext
     * @covers ::isNotFullCapturedAmountOnRefundContext
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @param float $orderTotalAmount
     * @param float $requestedAmount
     * @param float $orderCapturedAmount
     * @param float $orderRefundedAmount
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateFoundNextOrderState(
        $orderState,
        $transactionType,
        $transactionState,
        $orderTotalAmount,
        $requestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount
    ) {
        $this->postProcessData = $this->createPostProcessData(
            $orderState,
            $transactionType,
            $transactionState,
            $orderTotalAmount,
            $requestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );
        $handler = new PartialCaptured($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_PARTIAL_CAPTURED), $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::getNextHandler
     * @throws \ReflectionException
     */
    public function testGetNextHandler()
    {
        $reflectionMethod = new \ReflectionMethod($this->handler, "getNextHandler");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->handler);
        $this->assertEquals(null, $result);
    }
}
