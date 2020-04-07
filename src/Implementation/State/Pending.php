<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\TransitionData;

class Pending implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [new Success(), new Failed()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        if($transitionData->getTransactionType()->equals(new \Wirecard\Order\State\TransactionType\Success())) {
            return new Success();
        }
        return new Failed();
    }
}
