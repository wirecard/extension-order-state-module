<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

class Success implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [new Processing(), new Authorized()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        if ($this->isPurchase($transitionData)) {
            return new Processing();
        }
        return new Authorized();
    }
}
