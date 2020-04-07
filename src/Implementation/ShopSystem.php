<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType;

class ShopSystem
{

    /**
     * @var CreditCardTransactionType
     */
    private $creditCardTransactionType;

    public function __construct(CreditCardTransactionType $creditCardTransactionType)
    {
        $this->creditCardTransactionType = $creditCardTransactionType;
    }
}
