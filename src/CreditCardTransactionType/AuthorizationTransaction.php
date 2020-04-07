<?php


namespace Wirecard\Order\State\CreditCardTransactionType;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\CreditCardTransactionTypeHelper;

/**
 * Class AuthorizationTransaction
 * @package Wirecard\Order\State
 */
class AuthorizationTransaction implements CreditCardTransactionType
{
    use CreditCardTransactionTypeHelper;
}
