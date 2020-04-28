<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\EnumValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\StringValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException;

/**
 * Class OrderStateTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
 * @since 1.0.0
 */
class OrderStateTest extends \Codeception\Test\Unit
{
    /**
     * @var OrderState
     */
    protected $object;

    /**
     * @return \Generator
     */
    public function possibleValueSetDataProvider()
    {
        foreach (Constant::getOrderStates() as $state) {
            yield [$state];
        }
    }

    /**
     * @group unit
     * @small
     * @dataProvider possibleValueSetDataProvider
     * @covers ::possibleValueSet
     * @covers \Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant::getOrderStates
     * @param string $state
     * @throws InvalidValueObjectException
     */
    public function testPossibleValueSet($state)
    {
        $valueObject = new OrderState($state);
        $this->assertInstanceOf(OrderState::class, $valueObject);
        $this->assertInstanceOf(EnumValueObject::class, $valueObject);
        $this->assertInstanceOf(StringValueObject::class, $valueObject);
        $this->assertEquals($state, $valueObject);
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @covers ::guard
     */
    public function testInvalidOrderState()
    {
        $this->expectException(InvalidValueObjectException::class);
        new OrderState("INVALID_ORDER_STATE");
    }
}
