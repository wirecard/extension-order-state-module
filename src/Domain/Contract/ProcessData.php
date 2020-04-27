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
 * Interface ProcessData
 * @package Wirecard\ExtensionOrderStateModule\Domain\Contract
 * @since 1.0.0
 */
interface ProcessData
{
    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     */
    public function getOrderState();

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType
     */
    public function getTransactionType();

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState
     */
    public function getTransactionState();
}
