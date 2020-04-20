<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotificationHandler;

/**
 * Class Authorization
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification
 */
class Authorization extends InitialNotificationHandler
{
    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function getNextHandler()
    {
        return null;
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_PENDING)) &&
            $this->processData->getTransactionType()->equalsTo(
                new TransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE)
            )) {
            $result = new OrderState(Constant::ORDER_STATE_AUTHORIZED);
        }
        return $result;
    }
}
