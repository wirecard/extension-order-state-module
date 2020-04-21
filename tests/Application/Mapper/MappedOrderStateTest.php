<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Application\Mapper;

use Wirecard\ExtensionOrderStateModule\Application\Mapper\MappedOrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;

/**
 * Class MappedOrderStateTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Application\Mapper
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Application\Mapper\MappedOrderState
 * @since 1.0.0
 */
class MappedOrderStateTest extends \Codeception\Test\Unit
{
    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function mappedStateDataProvider()
    {
        yield "On external primitive type is string" => [new OrderState(Constant::ORDER_STATE_FAILED), 'F'];
        yield "On external value is '0'" => [
            new OrderState(Constant::ORDER_STATE_AUTHORIZED), '0'
        ];
        yield "On external primitive type is integer" => [new OrderState(Constant::ORDER_STATE_PENDING), 12];
        yield "On external value is 0" => [new OrderState(Constant::ORDER_STATE_PENDING), 0];
    }

    /**
     * @group integration
     * @small
     * @covers ::__construct
     * @covers ::getExternalState
     * @covers ::getInternalState
     * @dataProvider mappedStateDataProvider
     * @param OrderState $internalState
     * @param mixed $externalState
     */
    public function testConstructor($internalState, $externalState)
    {
        $mappedState = new MappedOrderState($internalState, $externalState);
        $this->assertEquals($internalState, $mappedState->getInternalState());
        $this->assertInstanceOf(OrderState::class, $mappedState->getInternalState());
        $this->assertEquals($externalState, $mappedState->getExternalState());
    }
}
