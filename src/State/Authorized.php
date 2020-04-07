<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
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
