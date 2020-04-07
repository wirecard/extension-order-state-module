<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

/**
 * Class Authorized
 * @package Wirecard\Order\State
 */
class Authorized implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
    }

    public function getNextState(TransitionData $transitionData)
    {
    }
}
