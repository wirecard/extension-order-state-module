<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

/**
 * Interface OrderStateManager
 * @package Wirecard\ExtensionOrderStateModule\Domain\Interfaces
 */
interface OrderStateManager
{

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return OrderState
     * @since 1.0.0
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper);
}
