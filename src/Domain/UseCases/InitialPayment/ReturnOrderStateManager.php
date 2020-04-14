<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Authorized;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\OrderStateValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Pending;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\Authorize;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\Debit;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\Purchase;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\UseCases\InputAdapterDTO;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class ReturnOrderStateManager implements OrderStateManager
{
    /**
     * @param InputAdapterDTO $internalDTO
     * @return bool
     */
    private function isStartedDebit(InputAdapterDTO $internalDTO)
    {
        return $internalDTO->getCurrentOrderState()->isStarted() &&
            $internalDTO->getTransactionType()->equalsTo(new Debit());
    }

    /**
     * @param InputAdapterDTO $internalDTO
     * @return bool
     */
    private function isStartedPayment(InputAdapterDTO $internalDTO)
    {
        return $internalDTO->getCurrentOrderState()->isStarted() &&
            $internalDTO->getTransactionType()->inSet([new Purchase(), new Authorize()]);
    }

    /**
     * @param InputAdapterDTO $internalDTO
     * @return bool
     */
    private function isPendingPurchase(InputAdapterDTO $internalDTO)
    {
        return $internalDTO->getCurrentOrderState()->equalsTo(new Pending()) &&
            $internalDTO->getTransactionType()->equalsTo(new Purchase());
    }

    /**
     * @param InputAdapterDTO $internalDTO
     * @return bool
     */
    private function isPendingAuthorization(InputAdapterDTO $internalDTO)
    {
        return $internalDTO->getCurrentOrderState()->equalsTo(new Pending()) &&
            $internalDTO->getTransactionType()->equalsTo(new Authorize());
    }

    /**
     * @param InputAdapterDTO $internalDTO
     * @return OrderStateValueObject
     */
    private function calculateOrderState(InputAdapterDTO $internalDTO)
    {
        if ($internalDTO->getTransactionState()->isFailure()) {
            return new Failed();
        }

        if ($this->isStartedPayment($internalDTO)) {
            return new Pending();
        }

        if ($this->isStartedDebit($internalDTO)) {
            return new Processing();
        }

        if ($this->isPendingPurchase($internalDTO)) {
            return new Processing();
        }

        if ($this->isPendingAuthorization($internalDTO)) {
            return new Authorized();
        }
    }

    /**
     * @param OrderStateValueObject $orderState
     * @param OrderStateMapper $mapper
     * @return string
     */
    public function toExternal(OrderStateValueObject $orderState, OrderStateMapper $mapper)
    {
        foreach ($mapper->map() as $externalType => $orderStateVO) {
            if ($orderState->equalsTo($orderStateVO)) {
                return $externalType;
            }
        }
    }

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $inputInternalDTO = new InputAdapterDTO($input);
        $orderState = $this->calculateOrderState($inputInternalDTO);
        return $this->toExternal($orderState, $mapper);
    }
}
