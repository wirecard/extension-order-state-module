<?php


namespace Wirecard\Order\State\TransactionType;

use Wirecard\Order\State\Implementation\TransactionTypeHelper;
use Wirecard\Order\State\TransactionType;

class None implements TransactionType
{
    use TransactionTypeHelper;
}
