<?php


namespace Wirecard\Order\State\TransactionType;

use Wirecard\Order\State\Implementation\StatefulUnaryValueObject;
use Wirecard\Order\State\TransactionType;

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