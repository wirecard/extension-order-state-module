<?php


namespace Wirecard\Order\State\State;

use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\TransactionType\Success as SuccessRemoteTransaction;

class Pending implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [new Success(), new Failed()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        if ($transitionData->getTransactionType()->equals(new SuccessRemoteTransaction())) {
            return new Success();
        }
        return new Failed();
    }
}
