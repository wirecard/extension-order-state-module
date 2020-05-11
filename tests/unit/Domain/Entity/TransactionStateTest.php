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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException;

/**
 * Class TransactionStateTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState
 * @since 1.0.0
 */
class TransactionStateTest extends \Codeception\Test\Unit
{
    /**
     * @var TransactionState
     */
    protected $object;

    /**
     * @return \Generator
     */
    public function possibleValueSetDataProvider()
    {
        foreach (Constant::getTransactionStates() as $state) {
            yield [$state];
        }
    }

    /**
     * @group unit
     * @small
     * @dataProvider possibleValueSetDataProvider
     * @covers ::possibleValueSet
     * @covers \Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant::getTransactionStates
     * @param string $state
     * @throws InvalidValueObjectException
     */
    public function testPossibleValueSet($state)
    {
        $valueObject = new TransactionState($state);
        $this->assertInstanceOf(TransactionState::class, $valueObject);
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
    public function testInvalidTransactionState()
    {
        $this->expectException(InvalidValueObjectException::class);
        new TransactionState("INVALID_TRANSACTION_STATE");
    }
}
