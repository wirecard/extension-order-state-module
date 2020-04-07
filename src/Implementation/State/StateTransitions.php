<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

interface StateTransitions
{

    /**
     * @return State[]
     */
    public function getPossibleNextStates();

    /**
     * @param TransitionData $transitionData
     * @return State
     */
    public function getNextState(TransitionData $transitionData);
}
