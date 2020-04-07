<?php


namespace Wirecard\Order\State;

/**
 * Interface State
 * @package Wirecard\Order\State
 *
 * This is the state of an order.
 *
 * The facade gets as input the current State of the order, and returns the desired next state of the order.
 * See OrderState for details.
 */
interface State
{

    public function equals(State $other);
}
