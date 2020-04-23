<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType\PurchaseTransaction;
use Wirecard\Order\State\State;
use Wirecard\Order\State\TransactionType\Success as SuccessRemoteTransaction;

/**
 * Trait StateHelper
 * @package Wirecard\Order\State\Implementation
 *
 * Provides additional type checking for equals for all states.
 */
trait StateHelper
{
    use StatefulUnaryValueObject;

    /**
     * @param StateHelper|State $other
     * @return bool
     */
    public function equals(State $other)
    {
        return $this->strictlyEquals($other);
    }


    /**
     * @param TransitionData $transitionData
     * @return bool
     */
    private function isPurchase(TransitionData $transitionData)
    {
        return $transitionData->getShopsystemCreditCardTransactionType()->equals(new PurchaseTransaction());
    }

    /**
     * @param TransitionData $transitionData
     * @return bool
     * @todo: name
     */
    private function isSuccessfulRemoteTransaction(TransitionData $transitionData)
    {
        return $transitionData->getTransactionType()->equals(new SuccessRemoteTransaction());
    }
}
