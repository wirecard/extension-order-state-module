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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\PartialRefunded;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class PartialRefundedTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\PartialRefunded
 * @since 1.0.0
 */
class PartialRefundedTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var PartialRefunded
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
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            40
        );
        $this->handler = new PartialRefunded($this->postProcessData);
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
        $refundableTypes = [
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_DEBIT,
            Constant::TRANSACTION_TYPE_CREDIT,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::TRANSACTION_TYPE_VOID_CAPTURE,
        ];
        $noneRefundableTypes = array_diff(Constant::getTransactionTypes(), $refundableTypes);
        foreach (Constant::getOrderStates() as $orderState) {
            foreach ($noneRefundableTypes as $noneRefundableType) {
                yield "none_refundable_{$noneRefundableType}_{$orderState}_on_refund_partial_scope" => [
                    $orderState,
                    $noneRefundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    30,
                    50,
                    0
                ];
            }


            foreach ($refundableTypes as $refundableType) {
                yield "refundable_{$refundableType}_{$orderState}_refund_amount_full_on_refund_partial_scope" => [
                    $orderState,
                    $refundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    100,
                    100,
                    0
                ];

                yield "refundable_{$refundableType}_{$orderState}_capture_amount_over_refund" => [
                    $orderState,
                    $refundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    40,
                    40.001,
                    0
                ];
            }
        }
    }


    /**
     * @group unit
     * @small
     * @dataProvider ignorableScenariosDataProvider
     * @covers ::calculate
     * @covers ::isNotFullRefundedAmount
     * @covers ::isRefundAmountOverCaptureAmount
     * @covers ::isAllowedTransactionType
     * @covers ::getCalculatedRefundTotalAmount
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
        $handler = new PartialRefunded($this->postProcessData);
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
        $refundableTypes = [
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_DEBIT,
            Constant::TRANSACTION_TYPE_CREDIT,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::TRANSACTION_TYPE_VOID_CAPTURE,
        ];
        foreach (Constant::getOrderStates() as $orderState) {
            foreach ($refundableTypes as $refundableType) {
                yield "refund_equal_greater_capture{$refundableType}_{$orderState}_on_refund_partial_scope" => [
                    $orderState,
                    $refundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    20,
                    50,
                    30
                ];
            }
        }
    }

    /**
     * @group unit
     * @small
     * @dataProvider nextStateCasesDataProvider
     * @covers ::calculate
     * @covers ::isNotFullRefundedAmount
     * @covers ::isRefundAmountOverCaptureAmount
     * @covers ::isAllowedTransactionType
     * @covers ::getCalculatedRefundTotalAmount
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @param $orderTotalAmount
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
        $handler = new PartialRefunded($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_PARTIAL_REFUNDED), $result);
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
        $this->assertEquals(new PartialCaptured($this->postProcessData), $result);
    }
}
