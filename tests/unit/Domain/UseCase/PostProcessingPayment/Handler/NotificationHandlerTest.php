<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment\Handler;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Failed;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class NotificationHandlerTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler
 * @since 1.0.0
 */
class NotificationHandlerTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var NotificationHandler
     */
    protected $handler;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _setUp()
    {
        $this->handler = new NotificationHandler($this->createDummyProcessData());
    }

    /**
     * @group unit
     * @small
     * @covers ::calculate
     * @throws \ReflectionException
     */
    public function testCalculate()
    {
        $reflectionMethod = new \ReflectionMethod($this->handler, "calculate");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->handler);
        $this->assertEquals(null, $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::getNextHandler
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testGetNextHandler()
    {
        $reflectionMethod = new \ReflectionMethod($this->handler, "getNextHandler");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->handler);
        $this->assertInstanceOf(Failed::class, $result);
        $this->assertEquals(new Failed($this->createDummyProcessData()), $result);
    }

    /**
     * @return \Generator
     */
    public function isFullyRefundedDataProvider()
    {
        yield [100, 100, true];
        yield [100, 99.99, false];
        yield [100, 30, false];
    }

    /**
     * @group unit
     * @small
     * @dataProvider isFullyRefundedDataProvider
     * @covers ::isFullAmountRequested
     * @param float $orderOpenAmount
     * @param float $requestedAmount
     * @param bool $expectedResult
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testIsFullyRefunded($orderOpenAmount, $requestedAmount, $expectedResult)
    {
        $successProcessData = $this->createPostProcessData(
            Constant::ORDER_STATE_STARTED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS,
            $orderOpenAmount,
            $requestedAmount
        );
        $handler = new NotificationHandler($successProcessData);
        $reflectionMethod = new \ReflectionMethod($handler, "isFullAmountRequested");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($handler);
        $this->assertEquals($expectedResult, $result);
    }
}
