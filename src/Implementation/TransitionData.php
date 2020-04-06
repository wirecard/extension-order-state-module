<?php


namespace Wirecard\Order\State\Implementation;

interface TransitionData
{

    /**
     * @return CreditCardTransactionType
     */
    public function getShopsystemCreditCardTransactionType();

}
