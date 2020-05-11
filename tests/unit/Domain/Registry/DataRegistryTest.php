<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Registry;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class DataRegistryTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\Registry
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry
 * @since 1.0.0
 */
class DataRegistryTest extends \Codeception\Test\Unit
{
    /**
     * @var DataRegistry
     */
    protected $dataRegistry;

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        $this->dataRegistry = $this->getMockForTrait(DataRegistry::class);
    }

    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function orderStatesDataProvider()
    {
        foreach (Constant::getOrderStates() as $state) {
            yield "Expected OrderState($state) VO" => [$state, new OrderState($state)];
        }
    }

    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function transactionTypesDataProvider()
    {
        foreach (Constant::getTransactionTypes() as $type) {
            yield "Expected TransactionType($type) VO" => [$type, new TransactionType($type)];
        }
    }

    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function transactionTypeRangeDataProvider()
    {
        yield [
            new TransactionType(Constant::TRANSACTION_TYPE_CREDIT),
            [Constant::TRANSACTION_TYPE_CREDIT, Constant::TRANSACTION_TYPE_REFUND_DEBIT],
            true
        ];

        yield [
            new TransactionType(Constant::TRANSACTION_TYPE_CREDIT),
            [Constant::TRANSACTION_TYPE_VOID_PURCHASE, Constant::TRANSACTION_TYPE_REFUND_DEBIT],
            false
        ];

        yield [
            new TransactionType(Constant::TRANSACTION_TYPE_PURCHASE),
            [Constant::TRANSACTION_TYPE_CREDIT, Constant::TRANSACTION_TYPE_REFUND_DEBIT],
            false
        ];

        yield [
            new TransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE),
            [Constant::TRANSACTION_TYPE_CREDIT, Constant::TRANSACTION_TYPE_AUTHORIZE],
            true
        ];

        foreach (Constant::getTransactionTypes() as $type) {
            yield [
                new TransactionType($type),
                Constant::getTransactionTypes(),
                true
            ];
        }
    }

    /**
     * @group unit
     * @small
     * @covers ::fromOrderStateRegistry
     * @dataProvider orderStatesDataProvider
     * @param string $state
     * @param OrderState $expectedOrderState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testFromOrderStateRegistry($state, $expectedOrderState)
    {
        $orderState = $this->dataRegistry->fromOrderStateRegistry($state);
        $this->assertEquals($expectedOrderState, $orderState);
        $this->assertInstanceOf(OrderState::class, $orderState);
    }

    /**
     * @group unit
     * @small
     * @covers ::fromOrderStateRegistry
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testFromOrderStateRegistryException()
    {
        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        $this->dataRegistry->fromOrderStateRegistry("X");
    }

    /**
     * @group unit
     * @small
     * @covers ::fromTransactionTypeRegistry
     * @dataProvider transactionTypesDataProvider
     * @param string $type
     * @param TransactionType $expectedOrderState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testFromTransactionTypeRegistry($type, $expectedOrderState)
    {
        $orderState = $this->dataRegistry->fromTransactionTypeRegistry($type);
        $this->assertEquals($expectedOrderState, $orderState);
        $this->assertInstanceOf(TransactionType::class, $orderState);
    }

    /**
     * @group unit
     * @small
     * @covers ::fromTransactionTypeRegistry
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testFromTransactionTypeRegistryException()
    {
        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        $this->dataRegistry->fromTransactionTypeRegistry("X");
    }

    /**
     * @group unit
     * @small
     * @covers ::isTransactionTypeInRange
     * @dataProvider transactionTypeRangeDataProvider
     * @param TransactionType $needed
     * @param array $range
     * @param bool $expectedResult
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testIsTransactionTypeInRange($needed, $range, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->dataRegistry->isTransactionTypeInRange($needed, $range));
    }


    /**
     * @group unit
     * @small
     * @covers ::fromTransactionTypeRegistry
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function testIsTransactionTypeInRangeException()
    {
        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        $this->dataRegistry->isTransactionTypeInRange(
            new TransactionType(Constant::TRANSACTION_TYPE_VOID_PURCHASE),
            ["X", "Y", "Z"]
        );
    }
}
