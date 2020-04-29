<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\EnumValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException;

/**
 * Class StringValueObjectTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\EnumValueObject
 * @since 1.0.0
 */
class EnumValueObjectTest extends \Codeception\Test\Unit
{
    const TYPE_A = "A";
    const TYPE_B = "B";

    /**
     * @var EnumValueObject
     */
    protected $object;

    /**
     * @return string[]
     */
    public function possibleValueSet()
    {
        return [self::TYPE_A, self::TYPE_B];
    }

    /**
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        parent::_before();
        $this->object = $this->getMockBuilder(EnumValueObject::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->method('possibleValueSet')->willReturn($this->possibleValueSet());
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @throws \ReflectionException
     */
    public function testConstructor()
    {
        $types = ["x", "y", "z"];
        $initiatedType = "x";
        /** @var EnumValueObject | MockObject $object */
        $object = $this->getMockForAbstractClass(
            EnumValueObject::class,
            [$initiatedType],
            "",
            true,
            true,
            true,
            ["possibleValueSet", "guard"]
        );
        $object->method('possibleValueSet')->willReturn($types);
        $reflection = new \ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty("value");
        $reflectionProperty->setAccessible(true);
        $this->assertEquals($initiatedType, $reflectionProperty->getValue($object));
    }

    /**
     * @group unit
     * @small
     * @covers ::possibleValueSet
     */
    public function testPossibleValueSet()
    {
        $this->assertEquals($this->possibleValueSet(), $this->object->possibleValueSet());
    }


    /**
     * @group unit
     * @small
     * @covers ::guard
     * @throws \ReflectionException
     */
    public function testGuard()
    {
        $this->expectException(InvalidValueObjectException::class);
        $class = new \ReflectionClass($this->object);
        $method = $class->getMethod('guard');
        $method->setAccessible(true);
        $method->invokeArgs($this->object, ["WRONG_VALUE"]);
    }
}
