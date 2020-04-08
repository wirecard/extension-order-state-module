<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

/**
 * Class Processing
 * @package Wirecard\Order\State
 * @codeCoverageIgnore
 *
 * In some cases, you can think of this as a success response.
 *
 * Particularly, this is success for purchase-type transactions.
 *
 * When an order is in this state, it means that its payment has been processed and it can be packaged, develivered, ...
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
