<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\StringValueObject;

abstract class TransactionStateValueObject extends StringValueObject
{
    /**
     * @return bool
     */
    public function isFailure()
    {
        return $this->equalsTo(new Failure());
    }
}
