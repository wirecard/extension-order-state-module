<?php


use Wirecard\Order\State\State;

class DummyOrder implements \Wirecard\Order\State\OrderDTO
{

    /**
     * @var State
     */
    private $currentState;

    public function __construct(State $currentState)
    {
        $this->currentState = $currentState;
    }

    /**
     * @return State the current state of the order
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }
}