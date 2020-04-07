<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\Implementation\StatefulUnaryValueObject;
use Wirecard\Order\State\State;

/**
 * Trait StateHelper
 * @package Wirecard\Order\State\Implementation\State
 *
 * Provides additional type checking for equals for all states.
 */
trait StateHelper
{
    use StatefulUnaryValueObject;

    /**
     * @param StateHelper|State $other
     * @return bool
     */
    public function equals(State $other)
    {
        return $this->strictlyEquals($other);
    }
}
