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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class Processing
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Processing
 * @since 1.0.0
 */
class ProcessingTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var Processing
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
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $this->handler = new Processing($this->postProcessData);
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
            [Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION]
        );
        $ignorableOrderStates = Constant::getOrderStates();
        $ignorableOrderStates = array_diff(
            $ignorableOrderStates,
            [Constant::ORDER_STATE_AUTHORIZED]
        );
        foreach ($ignorableOrderStates as $ignorableOrderState) {
            foreach ($ignorableTransactionTypes as $ignorableTransactionType) {
                yield "pp_notification_ignorable_{$ignorableOrderState}_{$ignorableTransactionType}_on_processing_scope" => [
                    $ignorableOrderState,
                    $ignorableTransactionType,
                    Constant::TRANSACTION_STATE_SUCCESS,
                    100, // orderTotal
                    100, // requestedAmount
                    0, // totalCapturedAmount
                    0 // totalRefundedAmount
                ];
            }
        }

        yield "ignorable_not_full_capture_authorized_capture-authorization_on_processing_scope" => [
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            30,
            69.99999,
            0
        ];

        yield "ignorable_once_refunded_authorized_capture-authorization_on_processing_scope" => [
            Constant::ORDER_STATE_AUTHORIZED,
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            30,
            50,
            20
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
        $handler = new Processing($this->postProcessData);
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
            Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            100,
            30,
            70,
            0
        );
        $handler = new Processing($this->postProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_PROCESSING), $result);
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
