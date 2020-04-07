<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\State\Failed;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

class Accepted implements CalculableState
{
    use \Wirecard\Order\State\Implementation\StateHelper;

    public function getPossibleNextStates()
    {
        return [];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return new Failed();
    }
}
