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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturnHandler;

/**
 * Class Started
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn
 */
class Started extends InitialReturnHandler
{
    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function getNextHandler()
    {
        return new Pending($this->processData);
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->processData->getTransactionType()->equalsTo(new TransactionType(Constant::TRANSACTION_TYPE_DEBIT))) {
            $result = new OrderState(Constant::ORDER_STATE_STARTED);
        }

        return $result;
    }
}
