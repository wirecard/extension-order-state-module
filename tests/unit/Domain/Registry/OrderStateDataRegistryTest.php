<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Registry;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\OrderStateDataRegistry;

/**
 * Class OrderStateDataRegistryTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Registry
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Registry\OrderStateDataRegistry
 * @since 1.0.0
 */
class OrderStateDataRegistryTest extends \Codeception\Test\Unit
{
    /**
     * @var OrderStateDataRegistry | \PHPUnit\Framework\MockObject\MockObject
     */
    protected $registry;

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        $this->registry = new OrderStateDataRegistry();
    }

    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function orderStateDataProvider()
    {
        foreach (Constant::getOrderStates() as $state) {
            yield "expecting value object for  {$state}" => [$state, new OrderState($state)];
        }
    }

    /**
     * @group unit
     * @small
     * @covers ::init
     * @dataProvider orderStateDataProvider
     * @param string $state
     * @param OrderState $expectedValueObject
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testInit($state, $expectedValueObject)
    {
        $result = $this->registry->get($state);
        $this->assertEquals($expectedValueObject, $result);
        $this->assertInstanceOf(OrderState::class, $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::getInstance
     * @throws \ReflectionException
     */
    public function testInstance()
    {
        $instance = OrderStateDataRegistry::getInstance();
        $reflectionProperty = new \ReflectionProperty($instance, "instance");
        $reflectionProperty->setAccessible(true);
        $this->assertEquals($instance, $reflectionProperty->getValue());
        $instance1 = OrderStateDataRegistry::getInstance();
        $this->assertEquals($instance, $instance1);
        $this->assertInstanceOf(OrderStateDataRegistry::class, $instance);
        $this->assertInstanceOf(OrderStateDataRegistry::class, $instance1);
    }
}
