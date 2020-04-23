<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Contract;

/**
 * Interface OrderStateMapDefinition
 * @package Wirecard\ExtensionOrderStateModule\Domain\Contract
 */
interface MappingDefinition
{
    /**
     * @return array
     * @since 1.0.0
     */
    public function definitions();
}
