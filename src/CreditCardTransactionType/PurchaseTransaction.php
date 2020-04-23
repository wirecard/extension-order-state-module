<?php


namespace Wirecard\Order\State\CreditCardTransactionType;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\CreditCardTransactionTypeHelper;

/**
 * Class PurchaseTransaction
 * @package Wirecard\Order\State
 */
class PurchaseTransaction implements CreditCardTransactionType
{
    use CreditCardTransactionTypeHelper;
}
