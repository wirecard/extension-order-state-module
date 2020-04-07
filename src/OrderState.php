<?php


namespace Wirecard\Order\State;

use Wirecard\Order\State\Implementation\Calculator;
use Wirecard\Order\State\Extension\CalculableState;

/**
 * Class OrderState is the only entry point to this subsystem.
 * @package Wirecard\Order\State
 *
 * The job of this type is to return the desired next state of an order, given the current state of the system.
 *
 * The state of the system is comprised of:
 *
 * - the state of the shopsystem
 * - the heuristic implemented by the shopsystem to map a state to another state
 * - the state of the order
 *
 * As a return value, the user gets a new State object. After getting this new and desired state of the order (the
 * method getNextState's return value), it is the responsability of the user to actually set the state of the
 * order.
 */
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

    /**
     * @param OrderDTO $order
     * @return State
     * @todo name
     */
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
