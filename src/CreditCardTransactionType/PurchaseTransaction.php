<?php


namespace Wirecard\Order\State\CreditCardTransactionType;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\CreditCardTransactionTypeHelper;

class PurchaseTransaction implements CreditCardTransactionType
{
    use CreditCardTransactionTypeHelper;
}
