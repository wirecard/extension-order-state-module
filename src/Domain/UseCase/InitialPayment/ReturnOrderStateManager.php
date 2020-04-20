<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InputAdapterDTO;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;

/**
 * Class ReturnOrderStateManager
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment
 */
class ReturnOrderStateManager implements OrderStateManager
{
    /**
     * @var InputAdapterDTO
     */
    private $internalInput;

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isStartedDebit()
    {
        return $this->internalInput->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->internalInput->getTransactionType()->equalsTo(new TransactionType(Constant::TRANSACTION_TYPE_DEBIT));
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isStartedPayment()
    {
        return $this->internalInput->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_STARTED)) &&
            $this->internalInput->getTransactionType()->inSet([
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
        return $this->internalInput->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_PENDING)) &&
            $this->internalInput->getTransactionType()->equalsTo(
                new TransactionType(Constant::TRANSACTION_TYPE_PURCHASE)
            );
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function isPendingAuthorization()
    {
        return $this->internalInput->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_PENDING)) &&
            $this->internalInput->getTransactionType()->equalsTo(
                new TransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE)
            );
    }

    /**
     * @return OrderState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    private function calculateOrderState()
    {
        if ($this->internalInput->getOrderState()->equalsTo(new OrderState(Constant::ORDER_STATE_FAILED)) ||
            $this->internalInput->getTransactionState()->equalsTo(
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

//    /**
//     * @param OrderStateValueObject $orderState
//     * @param OrderStateMapper $mapper
//     * @return string
//     * @throws \Exception
//     */
//    public function toExternal(OrderStateValueObject $orderState, OrderStateMapper $mapper)
//    {
//        $foundType = null;
//        foreach ($mapper->map() as $externalType => $orderStateVO) {
//            if ($orderState->equalsTo($orderStateVO)) {
//                $foundType = $externalType;
//                break;
//            }
//        }
//
//        if (null === $foundType) {
//            throw new \Exception("{$orderState} isn't defined in mapper!");
//        }
//
//        return $foundType;
//    }

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $this->internalInput = new InputAdapterDTO($input);
        $orderState = $this->calculateOrderState();
        return (string)$orderState;
    }
}
