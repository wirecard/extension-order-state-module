<?php


namespace Wirecard\Order\State;


use Wirecard\Order\State\Implementation\CreditCardTransactionType;

interface ShopSystemDTO
{

    public function mapState(State $state);

    /**
     * @return CreditCardTransactionType
     */
    public function getCreditCardTransactionType();
}