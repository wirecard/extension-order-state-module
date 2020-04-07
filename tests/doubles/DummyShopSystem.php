<?php


use Wirecard\Order\State\Implementation\CreditCardTransactionType as CreditCardTransactionType;
use Wirecard\Order\State\Implementation\State\Pending;
use Wirecard\Order\State\State;

class DummyShopSystem implements \Wirecard\Order\State\ShopSystemDTO
{

    /**
     * @var CreditCardTransactionType
     */
    private $transactionType;

    public function __construct(CreditCardTransactionType $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    public function mapState(State $state)
    {
        return $state;
    }

    /**
     * @return CreditCardTransactionType
     */
    public function getCreditCardTransactionType()
    {
        return $this->transactionType;
    }
}