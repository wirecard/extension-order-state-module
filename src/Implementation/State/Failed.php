<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\State;

class Failed implements State, StateTransitions
{
    use StateHelper;

    public function __construct()
    {
        $this->value = 2;
    }

    public function getPossibleNextStates()
    {
        return [];
    }
}
