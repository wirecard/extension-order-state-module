<?php


namespace Test\Double\Stub;

use Wirecard\Order\State\CreditCardTransactionType as CreditCardTransactionType;
use Wirecard\Order\State\State\Pending;
use Wirecard\Order\State\State;

class ShopSystemStub implements \Wirecard\Order\State\ShopSystem
{

    /**
     * @var \Wirecard\Order\State\CreditCardTransactionType
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
     * @return \Wirecard\Order\State\CreditCardTransactionType
     */
    public function getCreditCardTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return State[]
     */
    public function knownStates()
    {
        return [];
    }
}
