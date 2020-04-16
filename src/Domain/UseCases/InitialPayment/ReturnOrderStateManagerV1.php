<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCases\InputAdapterDTO;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\UseCases\OrderStateWrapper;

class ReturnOrderStateManagerV1 implements OrderStateManager
{
    /**
     * @var OrderStateWrapper
     */
    private $orderStateWrapper;
    /**
     * @var InputAdapterDTO
     */
    private $inputAdapterDTO;

    /**
     * ReturnOrderStateManager constructor.
     * @param OrderStateMapper $mapper
     * @param InputDataTransferObject $input
     */
    public function __construct(OrderStateMapper $mapper, InputDataTransferObject $input)
    {
        $this->inputAdapterDTO = new InputAdapterDTO($input);
        $this->orderStateWrapper = new OrderStateWrapper($mapper, $this->inputAdapterDTO->getCurrentOrderState());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function isStartedDebit()
    {
        return $this->orderStateWrapper->isStarted() &&
            $this->inputAdapterDTO->getTransactionType()->equalsTo(new Debit());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function isStartedPayment()
    {
        return $this->orderStateWrapper->isStarted() &&
            $this->inputAdapterDTO->getTransactionType()->inSet([new Purchase(), new Authorize()]);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function isPendingPurchase()
    {
        return $this->orderStateWrapper->isPending() &&
            $this->inputAdapterDTO->getTransactionType()->equalsTo(new Purchase());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function isPendingAuthorization()
    {
        return $this->orderStateWrapper->isPending() &&
            $this->inputAdapterDTO->getTransactionType()->equalsTo(new Authorize());
    }

    /**
     * @param InputAdapterDTO $internalDTO
     * @return OrderStateValueObject
     * @throws \Exception
     */
    private function calculateOrderState(InputAdapterDTO $internalDTO)
    {
        if ($internalDTO->getTransactionState()->equalsTo(new Failure())) {
            return $this->orderStateWrapper->get(Constant::ORDER_STATE_FAILED);
        }

        if ($this->orderStateWrapper->isFailed()) {
            return $this->orderStateWrapper->get(Constant::ORDER_STATE_FAILED);
        }

        if ($this->isStartedPayment()) {
            return $this->orderStateWrapper->get(Constant::ORDER_STATE_PENDING);
        }

        if ($this->isStartedDebit()) {
            return $this->orderStateWrapper->get(Constant::ORDER_STATE_PROCESSING);
        }

        if ($this->isPendingPurchase()) {
            return $this->orderStateWrapper->get(Constant::ORDER_STATE_PROCESSING);
        }

        if ($this->isPendingAuthorization()) {
            return $this->orderStateWrapper->get(Constant::ORDER_STATE_AUTHORIZED);
        }

        throw new \Exception("Can't compute next order state!");
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
     * @throws InvalidValueException
     * @throws \Exception
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $inputInternalDTO = new InputAdapterDTO($input);
        $orderState = $this->calculateOrderState($inputInternalDTO);
        return $this->toExternal($orderState, $mapper);
    }
}
