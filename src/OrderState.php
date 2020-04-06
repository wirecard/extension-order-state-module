<?php


namespace Wirecard\Order\State;

use Wirecard\Order\State\Implementation\Calculator;
use Wirecard\Order\State\Implementation\State\CalculableState;

class OrderState
{
    /**
     * @var Implementation\CreditCardTransactionType
     */
    private $ccTransactionType;

    public function __construct(ShopSystemDTO $shopSystemState)
    {
        $this->ccTransactionType = $shopSystemState->getCreditCardTransactionType();
    }

    public function getNextState(OrderDTO $order)
    {
        $calculableState = $this->toCalculableState($order->getCurrentState());
        $calculator = new Calculator($this->ccTransactionType, $calculableState);
        return $calculator->calculate();
    }

    /**
     * @param State $state
     * @return CalculableState
     */
    private function toCalculableState(State $state)
    {
        return $state;
    }
}
