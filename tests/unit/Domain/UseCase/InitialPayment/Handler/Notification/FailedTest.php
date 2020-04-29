<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\InitialPayment\Handler\Notification;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification\Processing;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class FailedTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification\Failed
 * @since 1.0.0
 */
class FailedTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var Failed
     */
    protected $handler;

    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData
     */
    private $initialProcessData;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    protected function _setUp()
    {
        $this->initialProcessData = $this->createInitialProcessData(
            Constant::ORDER_STATE_PENDING,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $this->handler = new Failed($this->initialProcessData);
    }

    /**
     * @group unit
     * @small
     */
    public function testDefinition()
    {
        $this->assertInstanceOf(
            \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\NotificationHandler::class,
            $this->handler
        );
    }

    /**
     * @return \Generator
     */
    public function ignorableScenariosDataProvider()
    {
        $ignorableOrderStates = Constant::getOrderStates();
        $ignorableOrderStates = array_diff($ignorableOrderStates, [Constant::ORDER_STATE_FAILED]);
        foreach ($ignorableOrderStates as $ignorableOrderState) {
            yield "initial_notification_ignorable_{$ignorableOrderState}_on_failed_handler" => [
                $ignorableOrderState,
                Constant::TRANSACTION_TYPE_PURCHASE,
                Constant::TRANSACTION_STATE_SUCCESS
            ];
        }
    }

    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @dataProvider ignorableScenariosDataProvider
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateResultIgnorable($orderState, $transactionType, $transactionState)
    {
        $this->initialProcessData = $this->createInitialProcessData(
            $orderState,
            $transactionType,
            $transactionState
        );
        $handler = new Failed($this->initialProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals(null, $result);
    }

    /**
     * @return \Generator
     */
    public function scopeSuccessScenariosDataProvider()
    {
        yield "initial_notification_order_state_failed_on_failed_handler" => [
            Constant::ORDER_STATE_FAILED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        ];

        yield "initial_notification_transaction_state_failed_on_failed_handler" => [
            Constant::ORDER_STATE_PENDING,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_FAILED
        ];
    }

    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @dataProvider scopeSuccessScenariosDataProvider
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateResultOrderFailedState($orderState, $transactionType, $transactionState)
    {
        $this->initialProcessData = $this->createInitialProcessData(
            $orderState,
            $transactionType,
            $transactionState
        );
        $handler = new Failed($this->initialProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_FAILED), $result);
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
        $this->assertInstanceOf(Processing::class, $result);
        $this->assertEquals(new Processing($this->initialProcessData), $result);
    }
}
