<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase;

use PHPUnit\Framework\MockObject\MockObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class AbstractProcessHandlerTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler
 * @since 1.0.0
 */
class AbstractProcessHandlerTest extends \Codeception\Test\Unit
{
    use MockCreator;
    /**
     * @var AbstractProcessHandler | MockObject
     */
    protected $processHandler;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        /** @var AbstractProcessHandler | MockObject $processHandler */
        $this->processHandler = $this->getMockForAbstractClass(
            AbstractProcessHandler::class,
            [$this->getSampleProcessData()]
        );
        $this->processHandler->method('calculate')->willReturn(null);
    }

    /**
     * @return object
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    protected function getSampleProcessData()
    {
        return $this->createDummyProcessData(
            Constant::ORDER_STATE_STARTED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        );
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testConstructor()
    {
        $reflection = new \ReflectionClass($this->processHandler);
        $reflectionProperty = $reflection->getProperty("processData");
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(
            $this->getSampleProcessData(),
            $reflectionProperty->getValue($this->processHandler)
        );
    }

    /**
     * @group unit
     * @small
     * @covers ::handle
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testHandleAbstract()
    {
        $this->processHandler->method('getNextHandler')->willReturn(null);
        $this->assertEquals(null, $this->processHandler->handle());
    }

    /**
     *
     * @group unit
     * @small
     * @covers ::handle
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testHandleWithNextHandler()
    {
        $processData = $this->getSampleProcessData();
        $nextProcessHandler = $this->getMockForAbstractClass(
            AbstractProcessHandler::class,
            [$processData]
        );
        $nextProcessHandler->method('calculate')->willReturnCallback(function () use ($processData) {
            if ($processData->getOrderState()->equalsTo($this->createOrderState(Constant::ORDER_STATE_STARTED))) {
                return $this->createOrderState(Constant::ORDER_STATE_PENDING);
            }
            return null;
        });
        $this->processHandler->method('getNextHandler')->willReturn($nextProcessHandler);
        $this->assertEquals(
            $this->createOrderState(Constant::ORDER_STATE_PENDING),
            $this->processHandler->handle()
        );
    }

    /**
     * @return \Generator
     */
    public function isSuccessTransactionDataProvider()
    {
        yield "is_transaction_successful_success" => [Constant::TRANSACTION_STATE_SUCCESS, true];
        yield "is_transaction_successful_failed" =>[Constant::TRANSACTION_STATE_FAILED, false];
    }

    /**
     * @group unit
     * @small
     * @dataProvider isSuccessTransactionDataProvider
     * @covers ::isSuccessTransaction
     * @param string $transactionState
     * @param bool $expectedResult
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testIsSuccessTransaction($transactionState, $expectedResult)
    {
        $successProcessData = $this->createInitialProcessData(
            Constant::ORDER_STATE_STARTED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            $transactionState
        );
        $handler = $this->getMockForAbstractClass(
            AbstractProcessHandler::class,
            [$successProcessData]
        );

        $reflectionMethod = new \ReflectionMethod($handler, "isSuccessTransaction");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($expectedResult, $result);
    }
}
