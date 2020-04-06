<?php


namespace Wirecard\Order\State\Implementation\State;


use Wirecard\Order\State\Implementation\TransitionData;

interface StateTransitions
{

    public function getPossibleNextStates();

    public function getNextState(TransitionData $transitionData);

}