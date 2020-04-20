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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturnHandler;

/**
 * Class Failed
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn
 */
class Failed extends InitialReturnHandler
{
    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function getNextHandler()
    {
        return new Started($this->processData);
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_FAILED)) ||
            $this->processData->getTransactionState()->equalsTo(
                new TransactionState(Constant::TRANSACTION_STATE_FAILURE)
            )) {
            $result = new OrderState(Constant::ORDER_STATE_FAILED);
        }

        return $result;
    }
}
