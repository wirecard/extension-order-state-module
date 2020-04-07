<?php


namespace Wirecard\Order\State;

/**
 * Interface OrderDTO
 * @package Wirecard\Order\State
 *
 * This is the DTO for orders as we need them. Users of this type must pass it in to the facade.
 */
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
