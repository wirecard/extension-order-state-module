<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;

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
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return new Failed();
    }
}
