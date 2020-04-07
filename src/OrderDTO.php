<?php


namespace Wirecard\Order\State;

interface OrderDTO
{

    /**
     * @return State the current state of the order
     */
    public function getCurrentState();

    /**
     * @return TransactionType as returned by the gateway API.
     */
    public function getTransactionType();
}
