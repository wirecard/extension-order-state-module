<?php


namespace Wirecard\Order\State\Implementation;

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
