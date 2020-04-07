<?php

/**
 * Class MallorysFirstOrder
 *
 * When ordering for the first time, Mallory tried to outright break the system by providing invalid data.
 */
class MallorysFirstOrder implements \Wirecard\Order\State\OrderDTO
{

    /**
     * @return \Wirecard\Order\State\State the current state of the order
     */
    public function getCurrentState()
    {
        return new \Wirecard\Order\State\State\Started();
    }
}