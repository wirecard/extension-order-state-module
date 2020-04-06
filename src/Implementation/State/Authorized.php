<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

class Authorized implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {

    }

    public function getNextState(TransitionData $transitionData)
    {

    }
}
