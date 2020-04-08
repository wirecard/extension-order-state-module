<?php


use Wirecard\Order\State\State;

class OrderStub implements \Wirecard\Order\State\Order
{

    /**
     * @var State
     */
    private $currentState;

    /**
     * @var \Wirecard\Order\State\TransactionType
     */
    private $transactionType;

    public function __construct(State $currentState, \Wirecard\Order\State\TransactionType $transactionType)
    {
        $this->currentState = $currentState;
        $this->transactionType = $transactionType;
    }

    /**
     * @return State the current state of the order
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * @return \Wirecard\Order\State\TransactionType as returned by the gateway API.
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param State $state
     */
    public function changeState(State $state)
    {
        $this->currentState = $state;
    }
}
