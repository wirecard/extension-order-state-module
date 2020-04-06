<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

class Accepted implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return new Failed();
    }
}
