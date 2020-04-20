<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;

/**
 * Class ReturnOrderStateManager
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment
 */
class ReturnOrderStateManager
{
    /**
     * @var ProcessData
     */
    private $processData;

    public function __construct(ProcessData $processData)
    {
        $this->processData = $processData;
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
    private function isStartedPayment()
    {
        return $this->processData->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->processData->getTransactionType()->inSet([
                Constant::TRANSACTION_TYPE_PURCHASE,
                Constant::TRANSACTION_TYPE_AUTHORIZE,
            ]);
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
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return OrderState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function handle()
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

        if ($this->isStartedDebit()) {
            return new OrderState(Constant::ORDER_STATE_PENDING);
        }

        if ($this->isPendingPurchase()) {
            return new OrderState(Constant::ORDER_STATE_PENDING);
        }

        if ($this->isPendingAuthorization()) {
            return new OrderState(Constant::ORDER_STATE_AUTHORIZED);
        }
    }
}
