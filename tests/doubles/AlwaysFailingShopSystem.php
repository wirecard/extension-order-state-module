<?php


class AlwaysFailingShopSystem implements \Wirecard\Order\State\ShopSystemDTO
{

    public function mapState(\Wirecard\Order\State\State $state)
    {
        // TODO: Implement mapState() method.
    }

    /**
     * @return \Wirecard\Order\State\CreditCardTransactionType
     */
    public function getCreditCardTransactionType()
    {
        // TODO: Implement getCreditCardTransactionType() method.
    }
}
