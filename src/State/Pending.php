<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\State\Success;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State\Failed;

class Pending implements \Wirecard\Order\State\Implementation\CalculableState
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
