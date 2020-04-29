<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\InitialPayment\Handler\InitialReturn;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn\Pending;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class FailedTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn\Failed
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
            Constant::ORDER_STATE_STARTED,
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
            \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\ReturnHandler::class,
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
        foreach (Constant::getTransactionTypes() as $transactionType) {
            foreach ($ignorableOrderStates as $ignorableOrderState) {
                yield "initial_notification_ignorable_{$transactionType}_{$ignorableOrderState}_on_failed_scope" => [
                    $ignorableOrderState,
                    $transactionType,
                    Constant::TRANSACTION_STATE_SUCCESS
                ];
            }
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
     * @group unit
     * @small
     * @covers ::calculate
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCalculateFoundNextOrderState()
    {
        $this->initialProcessData = $this->createInitialProcessData(
            Constant::ORDER_STATE_STARTED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_FAILED
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
        $this->assertInstanceOf(Pending::class, $result);
        $this->assertEquals(new Pending($this->initialProcessData), $result);
    }
}
