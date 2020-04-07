<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

/**
 * Class Pending
 * @package Wirecard\Order\State
 */
class Pending implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [new Success(), new Failed()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        if ($this->isSuccessfulRemoteTransaction($transitionData)) {
            return new Success();
        }
        return new Failed();
    }
}
