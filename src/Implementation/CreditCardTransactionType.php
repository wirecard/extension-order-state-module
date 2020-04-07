<?php


namespace Wirecard\Order\State\Implementation;

interface CreditCardTransactionType
{

    /**
     * @param CreditCardTransactionType $other
     * @return bool
     */
    public function equals(CreditCardTransactionType $other);
}
