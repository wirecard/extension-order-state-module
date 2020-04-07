<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\TransactionType;

/**
 * Interface TransitionData contains the data necessary to make the next transition.
 * @package Wirecard\Order\State\Implementation
 *
 * It esentially represents an edge along a finite-state machine.
 */
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
