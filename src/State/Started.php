<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

/**
 * Class Started
 * @package Wirecard\Order\State
 */
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
