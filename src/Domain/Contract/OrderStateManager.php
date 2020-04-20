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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;

/**
 * Interface OrderStateManager
 * @package Wirecard\ExtensionOrderStateModule\Domain\Contract
 */
interface OrderStateManager
{

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return OrderState
     * @since 1.0.0
     */
    public function process(ProcessData $processData);
}
