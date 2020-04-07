<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType;

class PurchaseTransaction implements CreditCardTransactionType
{
    use CreditCardTransactionTypeHelper;

    /**
     * @param CreditCardTransactionType $other
     * @return bool
     */
    public function equals(CreditCardTransactionType $other)
    {
        return $this->strictlyEquals($other);
    }
}
