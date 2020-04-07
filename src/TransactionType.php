<?php


namespace Wirecard\Order\State;

/**
 * Interface TransactionType
 * @package Wirecard\Order\State
 *
 * This is a value-object which stands for the transaction type, as received from the payment gateway.
 */
interface TransactionType
{
    /**
     * @param TransactionType $other
     * @return bool
     */
    public function equals(TransactionType $other);

}
