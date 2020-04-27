<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Contract;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;

/**
 * Interface OrderStateMapper
 * @package Wirecard\ExtensionOrderStateModule\Domain\Contract
 * @since 1.0.0
 */
interface OrderStateMapper
{
    /**
     * OrderStateMapper constructor.
     * @param MappingDefinition $definition
     */
    public function __construct(MappingDefinition $definition);

    /**
     * @return array
     */
    public function map();

    /**
     * @param OrderState $state
     * @return mixed
     */
    public function toExternal(OrderState $state);

    /**
     * @param mixed $externalState
     * @return OrderState
     */
    public function toInternal($externalState);
}
