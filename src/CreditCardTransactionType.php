<?php


namespace Wirecard\Order\State;

/**
 * Interface CreditCardTransactionType
 * @package Wirecard\Order\State
 *
 * The implementors of this type are types of credit-card settings, as specified in the shopsystem's backend.
 */
interface CreditCardTransactionType
{

    /**
     * @param CreditCardTransactionType $other
     * @return bool
     */
    public function equals(CreditCardTransactionType $other);
}
