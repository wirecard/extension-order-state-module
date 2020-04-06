<?php


namespace Wirecard\Order\State;


use Wirecard\Order\State\Implementation\Calculator;

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
        $calculator = new Calculator($this->ccTransactionType, $order->getCurrentState());
        return $calculator->calculate();
    }

}