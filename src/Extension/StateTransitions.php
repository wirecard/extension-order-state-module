<?php


namespace Wirecard\Order\State\Extension;

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
