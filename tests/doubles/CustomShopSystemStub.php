<?php


use Wirecard\Order\State\State\Pending;
use Wirecard\Order\State\State;

class CustomShopSystemStub extends ShopSystemStub implements \Wirecard\Order\State\ShopSystem
{

    public function mapState(State $state)
    {
        $hookedState = new Pending();
        if ($state->equals($hookedState)) {
            return new CustomPendingSuccess();
        }
        return $state;
    }


    /**
     * @return State[]
     */
    public function knownStates()
    {
        return [
            new Pending(),
            new CustomPendingSuccess(),
        ];
    }
}
