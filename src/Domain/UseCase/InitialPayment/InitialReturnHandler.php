<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;

use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;

/**
 * Class ReturnOrderStateManager
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment
 */
class InitialReturnHandler extends AbstractProcessHandler
{
    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isStartedPayment()
    {
        return $this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->processData->getTransactionType()->inSet([
                Constant::TRANSACTION_TYPE_PURCHASE,
                Constant::TRANSACTION_TYPE_AUTHORIZE,
            ]);
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        if ($this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_FAILED)) ||
            $this->processData->getTransactionState()->equalsTo(
                new TransactionState(Constant::TRANSACTION_STATE_FAILURE)
            )) {
            return new OrderState(Constant::ORDER_STATE_FAILED);
        }

        if ($this->isStartedPayment()) {
            return new OrderState(Constant::ORDER_STATE_PENDING);
        }
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
