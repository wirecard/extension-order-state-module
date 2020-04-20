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
 */
interface InputDataTransferObject
{
    /**
     * @return string
     * @since 1.0.0
     */
    public function getProcessType();
    /**
     * @return string
     * @since 1.0.0
     */
    public function getTransactionState();
    /**
     * @return string
     * @since 1.0.0
     */
    public function getTransactionType();
    /**
     * @return string
     * @since 1.0.0
     */
    public function getCurrentOrderState();
}
