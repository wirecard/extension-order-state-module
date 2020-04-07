<?php


namespace Wirecard\Order\State\State;


use Wirecard\Order\State\CreditCardTransactionType\PurchaseTransaction;
use Wirecard\Order\State\Implementation\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State\Authorized;
use Wirecard\Order\State\State\Processing;

class Success implements \Wirecard\Order\State\Implementation\CalculableState
{
    use \Wirecard\Order\State\Implementation\StateHelper;

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
