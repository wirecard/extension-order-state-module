<?php


namespace Wirecard\Order\State;

/**
 * Interface ShopSystemDTO
 * @package Wirecard\Order\State
 *
 * This is the specification of the shopsystem. It contains information which does not change during normal operation.
 * It also provides heuristics to map from a state to another state, which allows the shopsystem to hook itself into the
 * regular order state workflow.
 *
 * @todo name
 */
interface ShopSystemDTO
{

    public function mapState(State $state);

    /**
     * @return CreditCardTransactionType
     */
    public function getCreditCardTransactionType();
}
