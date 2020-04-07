<?php


namespace Wirecard\Order\State;

interface CreditCardTransactionType
{

    /**
     * @param CreditCardTransactionType $other
     * @return bool
     */
    public function equals(CreditCardTransactionType $other);
}
