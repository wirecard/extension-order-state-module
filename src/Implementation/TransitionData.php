<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\TransactionType;

interface TransitionData
{

    /**
     * @return CreditCardTransactionType
     */
    public function getShopsystemCreditCardTransactionType();

    /**
     * @return TransactionType
     */
    public function getTransactionType();

}
