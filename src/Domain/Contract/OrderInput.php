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
 * Interface OrderInput
 * @package Wirecard\ExtensionOrderStateModule\Domain\Contract
 * @since 1.0.0
 */
interface OrderInput
{
    /**
     * @return string
     */
    public function getCurrentOrderState();

    /**
     * @return float
     */
    public function getOrderTotalAmount();

    /**
     * @return float
     */
    public function getOrderRefundedAmount();

    /**
     * @return float
     */
    public function getOrderCapturedAmount();
}
