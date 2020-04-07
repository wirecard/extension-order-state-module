<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType;

/**
 * Trait CreditCardTransactionTypeHelper
 * @package Wirecard\Order\State\Implementation
 */
trait CreditCardTransactionTypeHelper
{
    use StatefulUnaryValueObject;

    /**
     * @param CreditCardTransactionTypeHelper|CreditCardTransactionType $other
     * @return bool
     */
    public function equals(CreditCardTransactionType $other)
    {
        return $this->strictlyEquals($other);
    }
}
