<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

class Failed implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [];
    }

    public function getNextState(TransitionData $transitionData)
    {
        throw new \RuntimeException("Once a payment has failed, nothing else can happen to it");
    }
}
