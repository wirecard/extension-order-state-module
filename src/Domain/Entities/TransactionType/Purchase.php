<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

class Purchase extends TransactionTypeValueObject
{
    /** @var string  */
    protected $value = Constant::TRANSACTION_TYPE_PURCHASE;
}
