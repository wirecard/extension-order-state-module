<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;

class Started implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [new Pending()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return new Pending();
    }
}
