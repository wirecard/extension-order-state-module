<?php


use Wirecard\Order\State\Implementation\State\Pending;
use Wirecard\Order\State\State;

class CustomShopSystem extends DummyShopSystem implements \Wirecard\Order\State\ShopSystemDTO
{

    public function mapState(State $state)
    {
        $hookedState = new Pending();
        if ($state->equals($hookedState)) {
            return new CustomPendingSuccess();
        }
        return $state;
    }
}