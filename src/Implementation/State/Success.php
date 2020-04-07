<?php


namespace Wirecard\Order\State\Implementation\State;


use Wirecard\Order\State\CreditCardTransactionType\PurchaseTransaction;
use Wirecard\Order\State\Implementation\TransitionData;

class Success implements CalculableState
{
    use StateHelper;

    public function getPossibleNextStates()
    {
        return [new Processing(), new Authorized()];
    }

    public function getNextState(TransitionData $transitionData)
    {
        if($transitionData->getShopsystemCreditCardTransactionType()->equals(new PurchaseTransaction())) {
            return new Processing();
        }
        return new Authorized();
    }
}
