<?php


use Wirecard\Order\State\Implementation\CreditCardTransactionType as CreditCardTransactionType;

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

    public function mapState(\Wirecard\Order\State\State $state)
    {
        // TODO: Implement mapState() method.
    }

    /**
     * @return CreditCardTransactionType
     */
    public function getCreditCardTransactionType()
    {
        return $this->transactionType;
    }
}