<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\Transition\ToPendingTransition;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

class Started implements State, StateTransitions
{
    use StateHelper;

    public function __construct()
    {
        $this->value = 5;
    }

    public function getPossibleNextStates()
    {
        return [new Pending(), new Failed()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        if ($transitionData instanceof ToPendingTransition) {
            return $this->transitionToPending($transitionData);
        }
        return new Failed();
    }

    private function transitionToPending(ToPendingTransition $toPendingTransition)
    {
    }
}
