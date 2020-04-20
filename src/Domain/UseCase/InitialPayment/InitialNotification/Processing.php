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
 * Class Processing
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification
 */
class Processing extends InitialNotificationHandler
{
    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function getNextHandler()
    {
        return new Authorization($this->processData);
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isStartedDebit()
    {
        return $this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->processData->getTransactionType()->equalsTo(new TransactionType(Constant::TRANSACTION_TYPE_DEBIT));
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isPendingPurchase()
    {
        return $this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_PENDING)) &&
            $this->processData->getTransactionType()->equalsTo(
                new TransactionType(Constant::TRANSACTION_TYPE_PURCHASE)
            );
    }

    /**
     * @inheritDoc
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @since 1.0.0
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->isStartedDebit() || $this->isPendingPurchase()) {
            $result = new OrderState(Constant::ORDER_STATE_PROCESSING);
        }

        return $result;
    }
}
