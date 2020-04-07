<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

class Failed implements CalculableState
{
    use \Wirecard\Order\State\Implementation\StateHelper;

    public function getPossibleNextStates()
    {
        return [];
    }

    public function getNextState(TransitionData $transitionData)
    {
        throw new \RuntimeException("Once a payment has failed, nothing else can happen to it");
    }
}
