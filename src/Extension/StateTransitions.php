<?php


namespace Wirecard\Order\State\Extension;

use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

/**
 * Interface StateTransitions
 * @package Wirecard\Order\State\Extension
 */
interface StateTransitions
{

    /**
     * Get the next possible states of the current state.
     * @return State[]
     *
     * While not necessary currently, it can be used to build the tree/graph of states, in order to check if is sane
     * and if it respects certain criteria, e.g. if it doesn't have cycles.
     */
    public function getPossibleNextStates();

    /**
     * Get the next state.
     *
     * @param TransitionData $transitionData
     * @return State
     *
     * The implementor specifies here the domain rules of how to get from the current state $this to the next state.
     * @todo: name
     */
    public function getNextState(TransitionData $transitionData);
}
