<?php


namespace Wirecard\Order\State\TransactionType;

use Wirecard\Order\State\Implementation\TransactionTypeHelper;
use Wirecard\Order\State\TransactionType;

/**
 * Class Success
 * @package Wirecard\Order\State
 */
class Success implements TransactionType
{
    use TransactionTypeHelper;
}
