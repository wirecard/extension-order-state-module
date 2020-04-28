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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\TransactionTypeDataRegistry;

/**
 * Class TransactionTypeDataRegistryTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Registry
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Registry\TransactionTypeDataRegistry
 * @since 1.0.0
 */
class TransactionTypeDataRegistryTest extends \Codeception\Test\Unit
{
    /**
     * @var TransactionTypeDataRegistry | \PHPUnit\Framework\MockObject\MockObject
     */
    protected $registry;

    protected function _before()
    {
        $this->registry = new TransactionTypeDataRegistry();
    }

    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function transactionTypeDataProvider()
    {
        foreach (Constant::getTransactionTypes() as $type) {
            yield "expecting value object for  {$type}" => [$type, new TransactionType($type)];
        }
    }

    /**
     * @group unit
     * @small
     * @covers ::init
     * @dataProvider transactionTypeDataProvider
     * @param string $type
     * @param OrderState $expectedValueObject
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testInit($type, $expectedValueObject)
    {
        $result = $this->registry->get($type);
        $this->assertEquals($expectedValueObject, $result);
        $this->assertInstanceOf(TransactionType::class, $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::getInstance
     * @covers ::$instance
     */
    public function testInstance()
    {
        $instance = TransactionTypeDataRegistry::getInstance();
        $reflectionProperty = new \ReflectionProperty($instance, "instance");
        $reflectionProperty->setAccessible(true);
        $this->assertEquals($instance, $reflectionProperty->getValue());
        $instance1 = TransactionTypeDataRegistry::getInstance();
        $this->assertEquals($instance, $instance1);
        $this->assertInstanceOf(TransactionTypeDataRegistry::class, $instance);
        $this->assertInstanceOf(TransactionTypeDataRegistry::class, $instance1);
    }
}
