<?php


namespace Wirecard\Order\State;

use Wirecard\Order\State\Implementation\Calculator;
use Wirecard\Order\State\Extension\CalculableState;

class OrderState
{
    /**
     * @var \Wirecard\Order\State\CreditCardTransactionType
     */
    private $ccTransactionType;
    /**
     * @var ShopSystemDTO
     * @todo extract an actual mapper
     */
    private $shopSystemState;

    public function __construct(ShopSystemDTO $shopSystemState)
    {
        $this->ccTransactionType = $shopSystemState->getCreditCardTransactionType();
        $this->shopSystemState = $shopSystemState;
    }

    public function getNextState(OrderDTO $order)
    {
        $mapped = $this->shopSystemState->mapState($order->getCurrentState());
        $calculableState = $this->toCalculableState($mapped);
        $calculator = new Calculator($this->ccTransactionType, $calculableState);
        return $calculator->calculate($order->getTransactionType());
    }

    /**
     * @param State|CalculableState $state
     * @return CalculableState
     */
    private function toCalculableState(State $state)
    {
        return $state;
    }
}
