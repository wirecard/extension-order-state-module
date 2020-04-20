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

interface OrderStateMapper
{
    /**
     * OrderStateMapper constructor.
     * @param MappingDefinition $definition
     * @since 1.0.0
     */
    public function __construct(MappingDefinition $definition);

    /**
     * @return array
     * @since 1.0.0
     */
    public function map();

    /**
     * @param OrderState $state
     * @return mixed
     * @since 1.0.0
     */
    public function toExternal(OrderState $state);
}
