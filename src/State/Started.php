<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State\Pending;

class Started implements CalculableState
{
    use \Wirecard\Order\State\Implementation\StateHelper;

    public function getPossibleNextStates()
    {
        return [new Pending()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return new Pending();
    }
}
