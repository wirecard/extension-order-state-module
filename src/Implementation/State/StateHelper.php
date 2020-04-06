<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\State;

trait StateHelper
{
    private $value;

    public function equals(State $other)
    {
        return $this->isStrictlyEqual($other);
    }

    private function isStrictlyEqual(StateHelper $other)
    {
        return $this->value === $other->value;
    }
}
