<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

class Debit extends TransactionTypeValueObject
{
    /** @var string  */
    protected $value = Constant::TRANSACTION_TYPE_DEBIT;
}
