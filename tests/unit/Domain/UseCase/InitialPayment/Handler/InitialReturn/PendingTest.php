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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn\Pending;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class PendingTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn\Pending
 * @since 1.0.0
 */
class PendingTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var Pending
     */
    protected $handler;

    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData
     */
    private $initialProcessData;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _setUp()
    {
        $this->initialProcessData = $this->createInitialProcessData(
            Constant::ORDER_STATE_STARTED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $this->handler = new Pending($this->initialProcessData);
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
        $ignorableOrderStates = array_diff($ignorableOrderStates, [Constant::ORDER_STATE_STARTED]);
        foreach (Constant::getTransactionTypes() as $transactionType) {
            foreach ($ignorableOrderStates as $ignorableOrderState) {
                yield "failed_ignorable_{$transactionType}_{$ignorableOrderState}_on_pending" => [
                    $ignorableOrderState,
                    $transactionType,
                    Constant::TRANSACTION_STATE_FAILED
                ];

                yield "success_ignorable_{$transactionType}_{$ignorableOrderState}_on_pending" => [
                    $ignorableOrderState,
                    $transactionType,
                    Constant::TRANSACTION_STATE_SUCCESS
                ];
            }
            yield [
                Constant::ORDER_STATE_STARTED,
                $transactionType,
                Constant::TRANSACTION_STATE_FAILED
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
        $handler = new Pending($this->initialProcessData);

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
        foreach (Constant::getTransactionTypes() as $scopeSuccessTransactionType) {
            yield "initial_notification_{$scopeSuccessTransactionType}_order_state_started_on_pending" => [
                Constant::ORDER_STATE_STARTED,
                $scopeSuccessTransactionType,
                Constant::TRANSACTION_STATE_SUCCESS
            ];
        }
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
    public function testCalculateFoundNextOrderState($orderState, $transactionType, $transactionState)
    {
        $this->initialProcessData = $this->createInitialProcessData(
            $orderState,
            $transactionType,
            $transactionState
        );
        $handler = new Pending($this->initialProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_PENDING), $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::getNextHandler
     * @throws \ReflectionException
     */
    public function testGetNextHandlerIsLastHandlerInTheLoop()
    {
        $reflectionMethod = new \ReflectionMethod($this->handler, "getNextHandler");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->handler);
        $this->assertEquals(null, $result);
    }
}
