<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;

class InitialNotificationHandler extends AbstractProcessHandler
{
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
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isPendingAuthorization()
    {
        return $this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_PENDING)) &&
            $this->processData->getTransactionType()->equalsTo(
                new TransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE)
            );
    }

    /**
     * @return OrderState|null
     */
    protected function calculate()
    {
        if ($this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_FAILED)) ||
            $this->processData->getTransactionState()->equalsTo(
                new TransactionState(Constant::TRANSACTION_STATE_FAILURE)
            )) {
            return new OrderState(Constant::ORDER_STATE_FAILED);
        }

        if ($this->isStartedDebit()) {
            return new OrderState(Constant::ORDER_STATE_PROCESSING);
        }

        if ($this->isPendingPurchase()) {
            return new OrderState(Constant::ORDER_STATE_PROCESSING);
        }

        if ($this->isPendingAuthorization()) {
            return new OrderState(Constant::ORDER_STATE_AUTHORIZED);
        }

        return null;
    }

    /**
     * @return AbstractProcessHandler|null
     * @since 1.0.0
     */
    protected function getNextHandler()
    {
        return null;
    }
}
