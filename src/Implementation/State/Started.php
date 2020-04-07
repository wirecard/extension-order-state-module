<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\Transition\ToPendingTransition;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

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
