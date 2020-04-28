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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\StringValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException;

/**
 * Class TransactionTypeTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType
 * @since 1.0.0
 */
class TransactionTypeTest extends \Codeception\Test\Unit
{
    /**
     * @var TransactionType
     */
    protected $object;

    /**
     * @return \Generator
     */
    public function possibleValueSetDataProvider()
    {
        foreach (Constant::getTransactionTypes() as $state) {
            yield [$state];
        }
    }

    /**
     * @group unit
     * @small
     * @dataProvider possibleValueSetDataProvider
     * @covers ::possibleValueSet
     * @covers \Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant::getTransactionTypes
     * @param string $state
     * @throws InvalidValueObjectException
     */
    public function testPossibleValueSet($state)
    {
        $valueObject = new TransactionType($state);
        $this->assertInstanceOf(TransactionType::class, $valueObject);
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
    public function testInvalidTransactionType()
    {
        $this->expectException(InvalidValueObjectException::class);
        new TransactionType("INVALID_TRANSACTION_TYPE");
    }
}
