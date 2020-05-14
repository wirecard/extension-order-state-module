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
 * Interface InputDataTransferObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Contract
 * @since 1.0.0
 * @todo: split to 2 Interfaces
 */
interface TransactionInput
{
    /**
     * @return string
     */
    public function getTransactionState();

    /**
     * @return string
     */
    public function getTransactionType();

    /**
     * @return float
     */
    public function getTransactionRequestedAmount();
}
