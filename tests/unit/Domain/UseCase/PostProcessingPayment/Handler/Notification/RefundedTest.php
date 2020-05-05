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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\PartialRefunded;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Refunded;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class RefundedTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Refunded
 * @since 1.0.0
 */
class RefundedTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var Refunded
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
            10,
            0,
            90
        );
        $this->handler = new Refunded($this->postProcessData);
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
                yield "none_refundable_{$noneRefundableType}_{$orderState}_on_refund_scope" => [
                    $orderState,
                    $noneRefundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    100,
                    100
                ];
            }
        }
    }


    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @covers ::isFullAmountRefunded
     * @dataProvider ignorableScenariosDataProvider
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @param float $orderOpenAmount
     * @param float $requestedAmount
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateResultIgnorable(
        $orderState,
        $transactionType,
        $transactionState,
        $orderOpenAmount,
        $requestedAmount
    ) {
        $this->postProcessData = $this->createPostProcessData(
            $orderState,
            $transactionType,
            $transactionState,
            $orderOpenAmount,
            $requestedAmount
        );
        $handler = new Refunded($this->postProcessData);
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
                yield "full_refundable_{$refundableType}_{$orderState}_on_refund_scope" => [
                    $orderState,
                    $refundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    20,
                    100,
                    80
                ];
                yield "full_refundable1_{$refundableType}_{$orderState}_on_refund_scope" => [
                    $orderState,
                    $refundableType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    100,
                    100,
                    0
                ];
            }
        }
    }

    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @covers ::isFullAmountRefunded
     * @dataProvider nextStateCasesDataProvider
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @param float $orderTotalAmount
     * @param float $transactionRequestedAmount
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
        $transactionRequestedAmount,
        $orderCapturedAmount,
        $orderRefundedAmount
    ) {
        $this->postProcessData = $this->createPostProcessData(
            $orderState,
            $transactionType,
            $transactionState,
            $orderTotalAmount,
            $transactionRequestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
        );
        $handler = new Refunded($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_REFUNDED), $result);
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
        $this->assertEquals(new PartialRefunded($this->postProcessData), $result);
    }
}
