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


    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        $this->registry = $this->getMockForAbstractClass(AbstractDataRegistry::class);
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
        $registry = $this->registry;
        $this->registry->method("init")->willReturnCallback(function () use (&$registry) {
            $reflectionMethod = new \ReflectionMethod($registry, "attach");
            $reflectionMethod->setAccessible(true);
            $reflectionMethod->invokeArgs($registry, ["x", 1]);
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
     * @throws \ReflectionException
     */
    public function testAttach($key, $value)
    {
        $reflectionMethod = new \ReflectionMethod($this->registry, "attach");
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invokeArgs($this->registry, [$key, $value]);
        $this->assertEquals($value, $this->registry->get($key));
    }

    /**
     * @group unit
     * @small
     * @covers ::get
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \ReflectionException
     */
    public function testGet()
    {
        $reflectionMethod = new \ReflectionMethod($this->registry, "attach");
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invokeArgs($this->registry, ["test", "TEST_GET"]);
        $this->assertEquals("TEST_GET", $this->registry->get("test"));

        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        $this->registry->get("NONE_EXISTING_KEY");
    }
}
