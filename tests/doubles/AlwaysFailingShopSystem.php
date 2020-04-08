<?php


use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\ShopSystem;
use Wirecard\Order\State\State;

class AlwaysFailingShopSystem implements ShopSystem
{

    public function mapState(State $state)
    {
        return $state;
    }

    /**
     * @return CreditCardTransactionType
     */
    public function getCreditCardTransactionType()
    {
        return new CreditCardTransactionType\AuthorizationTransaction();
    }

    /**
     * @return State[]
     */
    public function knownStates()
    {
        return [];
    }

}
