<?php


namespace Wirecard\Order\State\CreditCardTransactionType;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\CreditCardTransactionTypeHelper;

class AuthorizationTransaction implements CreditCardTransactionType
{
    use CreditCardTransactionTypeHelper;
}
