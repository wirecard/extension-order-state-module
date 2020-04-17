<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Mapper;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\MapDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\OrderStateDataRegistry;

/**
 * Class GenericOrderStateMapper
 * @package Wirecard\ExtensionOrderStateModule\Application\Mapper
 */
class GenericOrderStateMapper implements OrderStateMapper
{
    /**
     * @var MapDefinition
     */
    private $definition;

    /**
     * @var array
     */
    private $map = [];

    /**
     * GenericOrderStateMapper constructor.
     * @param MapDefinition $definition
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\NotInRegistryException
     * @since 1.0.0
     */
    public function __construct(MapDefinition $definition)
    {
        $this->definition = $definition;
        $this->map();
    }

    /**
     * @return array
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\NotInRegistryException
     */
    public function map()
    {
        if (!empty($this->map)) {
            foreach ($this->definition->map() as $externalState => $internalState) {
                $this->map[] = [OrderStateDataRegistry::getInstance()->get($internalState), $externalState];
            }
        }
        return $this->map;
    }

    /**
     * @param OrderState $state
     * @return string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\NotInRegistryException
     */
    public function toEternal(OrderState $state)
    {
        $resultExternalState = "";
        foreach ($this->map() as $state) {
            /** @var OrderState $internal */
            list($internal, $external) = $state;
            if ($state->equalsTo($internal)) {
                $resultExternalState = $external;
                break;
            }
        }

        if (!strlen($resultExternalState)) {
            throw new \Exception("There is not found mapping for {$state}");
        }
        return $resultExternalState;
    }
}
