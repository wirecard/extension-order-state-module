<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Mapper;

use Wirecard\ExtensionOrderStateModule\Application\Exception\MapReferenceNotFound;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class GenericOrderStateMapper
 * @package Wirecard\ExtensionOrderStateModule\Application\Mapper
 * @since 1.0.0
 */
class GenericOrderStateMapper implements OrderStateMapper
{
    use DataRegistry;

    /**
     * @var MappingDefinition
     */
    private $mappingDefinition;

    /**
     * @var array|MappedOrderState[]
     */
    private $map = [];

    /**
     * GenericOrderStateMapper constructor.
     * @param MappingDefinition $definition
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @since 1.0.0
     */
    public function __construct(MappingDefinition $definition)
    {
        $this->mappingDefinition = $definition;
        $this->map();
    }

    /**
     * @return array|MappedOrderState[]
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function map()
    {
        if (empty($this->map)) {
            foreach ($this->mappingDefinition->definitions() as $externalState => $internalState) {
                $this->map[] = new MappedOrderState($this->fromOrderStateRegistry($internalState), $externalState);
            }
        }
        return $this->map;
    }

    /**
     * @param OrderState $state
     * @return string
     * @throws MapReferenceNotFound
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function toExternal(OrderState $state)
    {
        $newExternalState = null;
        foreach ($this->map() as $mappedState) {
            if ($state->equalsTo($mappedState->getInternalState())) {
                $newExternalState = $mappedState->getExternalState();
                break;
            }
        }

        if (is_null($newExternalState)) {
            throw new MapReferenceNotFound("There is not found mapping for {$state}");
        }
        return $newExternalState;
    }

    /**
     * @param mixed $externalState
     * @return OrderState
     * @throws MapReferenceNotFound
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function toInternal($externalState)
    {
        $mappingDefinition = $this->mappingDefinition->definitions();
        if (!isset($mappingDefinition[$externalState])) {
            throw new MapReferenceNotFound("There is not found mapping for {$externalState}");
        }

        return $this->fromOrderStateRegistry($mappingDefinition[$externalState]);
    }
}
