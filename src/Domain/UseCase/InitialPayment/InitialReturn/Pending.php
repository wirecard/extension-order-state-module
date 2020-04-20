<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturnHandler;

/**
 * Class Pending
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn
 */
class Pending extends InitialReturnHandler
{
    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->processData->getTransactionType()->inSet([
                Constant::TRANSACTION_TYPE_PURCHASE,
                Constant::TRANSACTION_TYPE_AUTHORIZE,
            ])) {
            $result = new OrderState(Constant::ORDER_STATE_PENDING);
        }

        return $result;
    }
}
