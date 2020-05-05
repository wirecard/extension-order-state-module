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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Canceled;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class CanceledTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Canceled
 * @since 1.0.0
 */
class CanceledTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var Canceled
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
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $this->handler = new Canceled($this->postProcessData);
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
        $ignorableTransactionTypes = Constant::getTransactionTypes();
        $ignorableTransactionTypes = array_diff(
            $ignorableTransactionTypes,
            [Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION]
        );
        $ignorableOrderStates = Constant::getOrderStates();
        $ignorableOrderStates = array_diff(
            $ignorableOrderStates,
            [Constant::ORDER_STATE_AUTHORIZED]
        );
        foreach ($ignorableOrderStates as $ignorableOrderState) {
            foreach ($ignorableTransactionTypes as $ignorableTransactionType) {
                yield "ignorable_types_{$ignorableOrderState}_{$ignorableTransactionType}_on_canceled_scope" => [
                    $ignorableOrderState,
                    $ignorableTransactionType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100,
                    100,
                    0,
                    0
                ];
            }
        }

        yield "not_full-requested_amount_authorized_void-authorization_on_canceled_scope" => [
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            99.999,
            0,
            0
        ];

        yield "once_captured_authorized_void-authorization_on_canceled_scope" => [
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            100,
            50,
            0,
        ];

        yield "once_refunded_authorized_void-authorization_on_canceled_scope" => [
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            100,
            0,
            50
        ];

        yield "once_refunded_and_captured_authorized_void-authorization_on_canceled_scope" => [
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            100,
            30,
            30
        ];
    }


    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @dataProvider ignorableScenariosDataProvider
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
    public function testCalculateResultIgnorable(
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
        $handler = new Canceled($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals(null, $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateFoundNextOrderState()
    {
        $this->postProcessData = $this->createPostProcessData(
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            100
        );
        $handler = new Canceled($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_CANCELED), $result);
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
        $this->assertEquals(new Processing($this->postProcessData), $result);
    }
}
