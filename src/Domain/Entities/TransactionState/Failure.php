<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

class Failure extends TransactionStateValueObject
{
    protected $value = Constant::TRANSACTION_STATE_FAILURE;
}
