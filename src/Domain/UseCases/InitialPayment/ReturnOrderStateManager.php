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
use Wirecard\ExtensionOrderStateModule\Domain\UseCases\InputAdapterDTO;
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
     * @throws \Exception
     */
    private function calculateOrderState(InputAdapterDTO $internalDTO)
    {
        $newState = null;

        if ($internalDTO->getCurrentOrderState()->equalsTo(new Failed()) ||
            $internalDTO->getTransactionState()->isFailure()) {
            $newState = new Failed();
        }

        if ($this->isStartedPayment($internalDTO)) {
            $newState = new Pending();
        }

        if ($this->isStartedDebit($internalDTO)) {
            $newState = new Processing();
        }

        if ($this->isPendingPurchase($internalDTO)) {
            $newState = new Processing();
        }

        if ($this->isPendingAuthorization($internalDTO)) {
            $newState = new Authorized();
        }

        if (null === $newState) {
            throw new \Exception("Can't compute next order state!");
        }
        return $newState;
    }

    /**
     * @param OrderStateValueObject $orderState
     * @param OrderStateMapper $mapper
     * @return string
     * @throws \Exception
     */
    public function toExternal(OrderStateValueObject $orderState, OrderStateMapper $mapper)
    {
        $foundType = null;
        foreach ($mapper->map() as $externalType => $orderStateVO) {
            if ($orderState->equalsTo($orderStateVO)) {
                $foundType = $externalType;
                break;
            }
        }

        if (null === $foundType) {
            throw new \Exception("{$orderState} isn't defined in mapper!");
        }

        return $foundType;
    }

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException
     * @throws \Exception
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $inputInternalDTO = new InputAdapterDTO($input);
        $orderState = $this->calculateOrderState($inputInternalDTO);
        return $this->toExternal($orderState, $mapper);
    }
}
