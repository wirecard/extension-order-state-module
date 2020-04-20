<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Mapper;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MapDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\MapReferenceNotFound;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class GenericOrderStateMapper
 * @package Wirecard\ExtensionOrderStateModule\Application\Mapper
 */
class GenericOrderStateMapper implements OrderStateMapper
{
    use DataRegistry;

    /**
     * @var MapDefinition
     */
    private $definition;

    /**
     * @var array|MappedOrderState[]
     */
    private $map = [];

    /**
     * GenericOrderStateMapper constructor.
     * @param MapDefinition $definition
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @since 1.0.0
     */
    public function __construct(MapDefinition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return array|MappedOrderState[]
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function map()
    {
        if (empty($this->map)) {
            foreach ($this->definition->map() as $externalState => $internalState) {
                $this->map[] = new MappedOrderState($this->fromOrderStateRegistry($internalState), $externalState);
            }
        }
        return $this->map;
    }

    /**
     * @param OrderState|ValueObject $state
     * @return string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws MapReferenceNotFound
     */
    public function toExternal(ValueObject $state)
    {
        $newExternalState = null;
        foreach ($this->map() as $mappedState) {
            if ($mappedState->getInternalState()->equalsTo($state)) {
                $newExternalState = $mappedState->getExternalState();
                break;
            }
        }

        if (is_null($newExternalState)) {
            throw new MapReferenceNotFound("There is not found mapping for {$state}");
        }
        return $newExternalState;
    }
}
