<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\InitialPayment\Handler;

use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\InitialReturn\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\ReturnHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class ReturnHandlerTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\ReturnHandler
 * @since 1.0.0
 */
class ReturnHandlerTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var ReturnHandler
     */
    protected $handler;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _setUp()
    {
        $this->handler = new ReturnHandler($this->createDummyProcessData());
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
}
