<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Application\Mapper;

use PHPUnit\Framework\MockObject\MockObject;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\MappedOrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;

/**
 * Class GenericOrderStateMapperTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Application\Mapper
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper
 * @since 1.0.0
 */
class GenericOrderStateMapperTest extends \Codeception\Test\Unit
{
    const EXTERNAL_ORDER_STATE_AUTHORIZED = "authorized";
    const EXTERNAL_ORDER_STATE_STARTED = "started";
    const EXTERNAL_ORDER_STATE_PENDING = "pending";
    const EXTERNAL_ORDER_STATE_PROCESSING = "processing";
    const EXTERNAL_ORDER_STATE_FAILED = "failed";

    /**
     * @var GenericOrderStateMapper
     */
    private $mapper;

    /**
     * @var MappingDefinition| MockObject $mapDefinition
     */
    private $mapDefinition;

    /**
     * @return array
     * @since 1.0.0
     */
    private function getSampleMapDefinition()
    {
        return [
            self::EXTERNAL_ORDER_STATE_STARTED => Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING => Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_FAILED => Constant::ORDER_STATE_FAILED,
            self::EXTERNAL_ORDER_STATE_AUTHORIZED => Constant::ORDER_STATE_AUTHORIZED,
            self::EXTERNAL_ORDER_STATE_PROCESSING => Constant::ORDER_STATE_PROCESSING,
        ];
    }

    /**
     * @return \Generator
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function toExternalDataProvider()
    {
        foreach ($this->getSampleMapDefinition() as $external => $internal) {
            yield "external:{$external} / internal:{$internal}" => [
                $external,
                new OrderState($internal),
                $external
            ];
        }
    }

    /**
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        // Implement MapDefinition
        /** @var MappingDefinition| MockObject $mapDefinition */
        $this->mapDefinition = \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => $this->getSampleMapDefinition()
        ]);
    }

    /**
     * @group integration
     * @small
     * @covers ::map
     * @covers ::__construct
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testMapGeneration()
    {
        $this->mapper = new GenericOrderStateMapper($this->mapDefinition);
        $map = $this->mapper->map();
        $this->assertTrue(is_array($map));
        $mappedState = array_shift($map);
        $this->assertInstanceOf(MappedOrderState::class, $mappedState, "Instance of " . MappedOrderState::class);
    }

    /**
     * @group integration
     * @small
     * @covers ::map
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function testMapException()
    {
        $this->expectException(\Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException::class);
        $mapDefinition = \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => ['EXTERNAL_STATE' => 'INVALID_INTERNAL_STATE']
        ]);
        $this->mapper = new GenericOrderStateMapper($mapDefinition);
        $this->mapper->map();
    }

    /**
     * @group integration
     * @small
     * @covers ::toExternal
     * @dataProvider toExternalDataProvider
     * @param mixed $expectedState
     * @param OrderState $internal
     * @throws \Wirecard\ExtensionOrderStateModule\Application\Exception\MapReferenceNotFound
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testInternalToExternal($expectedState, $internal)
    {
        $this->mapper = new GenericOrderStateMapper($this->mapDefinition);
        $externalState = $this->mapper->toExternal($internal);
        $this->assertEquals($expectedState, $externalState);
    }

    /**
     * @group integration
     * @small
     * @covers ::toExternal
     * @throws \Wirecard\ExtensionOrderStateModule\Application\Exception\MapReferenceNotFound
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function testInternalToExternalException()
    {
        $mapDefinition = \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => ['E1' => Constant::ORDER_STATE_AUTHORIZED]
        ]);
        $this->mapper = new GenericOrderStateMapper($mapDefinition);

        $this->expectException(\Wirecard\ExtensionOrderStateModule\Application\Exception\MapReferenceNotFound::class);
        $this->mapper->toExternal(new OrderState(Constant::ORDER_STATE_FAILED));
    }

    /**
     * @return \Generator
     */
    public function toInternalDataProvider()
    {
        $mapDefinition = $this->getSampleMapDefinition();
        foreach ($this->getSampleMapDefinition() as $externalState => $internalState) {
            yield [$externalState, $internalState, $mapDefinition];
        }
    }

    /**
     * @group integration
     * @small
     * @covers ::toInternal
     * @dataProvider toInternalDataProvider
     * @param string $externalState
     * @param string $internalState
     * @param array $definition
     * @throws \Wirecard\ExtensionOrderStateModule\Application\Exception\MapReferenceNotFound
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function testExternalToInternal($externalState, $internalState, $definition)
    {
        $this->mapper = new GenericOrderStateMapper(\Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => $definition
        ]));
        $this->assertEquals(new OrderState($internalState), $this->mapper->toInternal($externalState));
    }

    /**
     * @group integration
     * @small
     * @covers ::toInternal
     * @throws \Wirecard\ExtensionOrderStateModule\Application\Exception\MapReferenceNotFound
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testToInternalException()
    {
        $this->mapper = new GenericOrderStateMapper($this->mapDefinition);
        $this->expectException(
            \Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException::class
        );
        $this->mapper->toInternal("not_referencing_or_not_defined_order_state");
    }
}
