<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\StringValueObject;

/**
 * Class StringValueObjectTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\StringValueObject
 * @since 1.0.0
 */
class StringValueObjectTest extends \Codeception\Test\Unit
{
    /**
     * @var StringValueObject
     */
    protected $object;

    /**
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        parent::_before();
        $this->object = $this->getMockBuilder(StringValueObject::class)->setMethodsExcept([
            "__toString",
        ])->getMock();
    }

    /**
     * @return \Generator
     */
    public function stringDataProvider()
    {
        yield ["test"];
        yield ["1"];
        yield ["null"];
        yield ["true"];
        yield ["bool"];
        yield ["000"];
    }

    /**
     * @group unit
     * @small
     * @covers ::__toString
     * @dataProvider stringDataProvider
     * @param string $value
     * @throws \ReflectionException
     */
    public function testToString($value)
    {
        $reflection = new \ReflectionClass($this->object);
        $reflectionProperty = $reflection->getProperty("value");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->object, $value);
        $this->assertEquals($value, (string)$this->object);
    }


    /**
     * @group unit
     * @small
     * @covers ::equalsTo
     * @dataProvider stringDataProvider
     * @param string $value
     * @throws \ReflectionException
     */
    public function testEqualsTo($value)
    {
        /** @var StringValueObject $object */
        $object = $this->getMockBuilder(StringValueObject::class)->setMethodsExcept([
            "equalsTo",
        ])->getMock();
        $reflection = new \ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty("value");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);

        $otherObject = $this->getMockBuilder(StringValueObject::class)->setMethodsExcept([
            "equalsTo",
        ])->getMock();
        $reflection = new \ReflectionClass($otherObject);
        $reflectionProperty = $reflection->getProperty("value");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($otherObject, $value);

        $this->assertInstanceOf(StringValueObject::class, $object);
        $this->assertInstanceOf(StringValueObject::class, $otherObject);
        $this->assertTrue($object->equalsTo($otherObject));
    }
}
