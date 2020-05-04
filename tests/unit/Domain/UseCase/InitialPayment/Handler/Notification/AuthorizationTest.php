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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification\Authorization;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class AuthorizationTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification\Authorization
 * @since 1.0.0
 */
class AuthorizationTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var Authorization
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
            Constant::ORDER_STATE_PENDING,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $this->handler = new Authorization($this->initialProcessData);
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
        $scopeTypes = [
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
        ];
        foreach ($scopeTypes as $scopeType) {
            yield "initial_notification_ignorable_{$scopeType}_on_authorization_scope" =>
            [
                Constant::ORDER_STATE_PENDING,
                $scopeType,
                Constant::TRANSACTION_STATE_FAILED
            ];
        }

        $ignorableTransactionTypes = Constant::getTransactionTypes();
        $ignorableTransactionTypes = array_diff($ignorableTransactionTypes, $scopeTypes);
        foreach (Constant::getOrderStates() as $orderState) {
            foreach ($ignorableTransactionTypes as $ignorableTransactionType) {
                yield "initial_notification_ignorable_{$orderState}_{$ignorableTransactionType}_on_processing_scope" =>
                [
                    $orderState,
                    $ignorableTransactionType,
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
        $handler = new Authorization($this->initialProcessData);
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
        $scopeSuccessTransactionTypes = [
            Constant::TRANSACTION_TYPE_AUTHORIZATION,
        ];
        foreach (Constant::getOrderStates() as $orderState) {
            foreach ($scopeSuccessTransactionTypes as $scopeSuccessTransactionType) {
                yield "initial_notification_{$scopeSuccessTransactionType}_order_state_{$orderState}" => [
                    $orderState,
                    $scopeSuccessTransactionType,
                    Constant::TRANSACTION_STATE_SUCCESS
                ];
            }
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
    public function testCalculateResultOrderProcessingState($orderState, $transactionType, $transactionState)
    {
        $this->initialProcessData = $this->createInitialProcessData(
            $orderState,
            $transactionType,
            $transactionState
        );
        $handler = new Authorization($this->initialProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($this->createOrderState(Constant::ORDER_STATE_AUTHORIZED), $result);
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
