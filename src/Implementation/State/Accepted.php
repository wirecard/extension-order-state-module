<?php


namespace Wirecard\Order\State\Implementation\State;


use Wirecard\Order\State\State;

class Accepted implements State, StateTransitions
{
    use StateHelper;

    public function __construct()
    {
        $this->value = 6;
    }

    public function getPossibleNextStates()
    {
        return [];
    }
}