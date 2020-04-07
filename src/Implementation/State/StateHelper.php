<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\Implementation\StatefulUnaryValueObject;
use Wirecard\Order\State\State;

/**
 * Trait StateHelper
 * @package Wirecard\Order\State\Implementation\State
 *
 * This can be a trait or an abstract base class.
 *
 * Either way, free the inheritance tree and leave it to the domain by using traits, once support for php 5.6 is
 * dropped.
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
