<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\TransactionType;

/**
 * Trait TransactionTypeHelper
 * @package Wirecard\Order\State\Implementation
 */
trait TransactionTypeHelper
{
    use StatefulUnaryValueObject;

    /**
     * @param TransactionTypeHelper|TransactionType $other
     * @return bool
     */
    public function equals(TransactionType $other)
    {
        return $this->strictlyEquals($other);
    }
}
