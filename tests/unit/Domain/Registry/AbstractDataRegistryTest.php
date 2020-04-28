<?php

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Registry;

use PHPUnit\Framework\MockObject\MockObject;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\AbstractDataRegistry;

/**
 * Class AbstractDataRegistryTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Registry
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Registry\AbstractDataRegistry
 * @since 1.0.0
 */
class AbstractDataRegistryTest extends \Codeception\Test\Unit
{
    /**
     * @var AbstractDataRegistry | MockObject
     */
    protected $registry;


    protected function _before()
    {
        $this->registry = $this->getMockBuilder(AbstractDataRegistry::class)
            ->onlyMethods(["init"])
            ->disableOriginalConstructor()->getMock();
        $this->registry->method("init")->willReturnCallback(function () {
        });
    }

    /**
     * @return \Generator
     */
    public function attachDataProvider()
    {
        yield  "int_value" => ["a", 1];
        yield "double_value" => ["b", 1.1];
        yield "bool" => ["c", true];
        yield "string" => ["d", "test"];
        $object = new \stdClass();
        $object->x = 123;
        // int key
        yield "object" => [1, $object];
    }

    /**
     * @group unit
     * @small
     * @covers ::init
     * @covers ::__construct
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testInit()
    {
        /** @var AbstractDataRegistry | MockObject $registry */
        $registry = $this->getMockBuilder(AbstractDataRegistry::class)
            ->onlyMethods(["init"])
            ->disableOriginalConstructor()->getMock();
        $registry->method("init")->willReturnCallback(function () use (&$registry) {
            $registry->attach("x", 1);
        });

        $class = new \ReflectionClass($registry);
        $method = $class->getMethod('init');
        $method->setAccessible(true);
        $method->invoke($registry);

        $this->assertEquals(1, $registry->get("x"));
    }

    /**
     * @group unit
     * @small
     * @covers ::attach
     * @dataProvider attachDataProvider
     * @param string $key
     * @param mixed $value
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testAttach($key, $value)
    {
        $this->registry->attach($key, $value);
        $this->assertEquals($value, $this->registry->get($key));
    }

    /**
     * @group unit
     * @small
     * @covers ::get
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testGet()
    {
        $this->registry->attach("test", "TEST_GET");
        $this->assertEquals("TEST_GET", $this->registry->get("test"));

        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        $this->registry->get("NONE_EXISTING_KEY");
    }
}
