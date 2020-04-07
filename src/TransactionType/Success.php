<?php


namespace Wirecard\Order\State\TransactionType;

use Wirecard\Order\State\Implementation\TransactionTypeHelper;
use Wirecard\Order\State\TransactionType;

class Success implements TransactionType
{
    use TransactionTypeHelper;
}
