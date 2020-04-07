<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State\Failed;

/**
 * Class Processing
 * @package Wirecard\Order\State\Implementation\State
 *
 * In some cases, you can think of this as a success response.
 *
 * Particularly, this is success for purchase-type transactions.
 */
class Processing implements CalculableState
{
    use \Wirecard\Order\State\Implementation\StateHelper;

    public function getPossibleNextStates()
    {
        return [];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return new Failed();
    }
}
