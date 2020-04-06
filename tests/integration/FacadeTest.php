<?php

use Wirecard\Order\State\Implementation\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\PurchaseTransaction;
use Wirecard\Order\State\Implementation\State\Accepted;
use Wirecard\Order\State\Implementation\State\Started;
use Wirecard\Order\State\OrderState;
use Wirecard\Order\State\State;

class FacadeTest extends PHPUnit_Framework_TestCase
{
    public function simple_cases_provider()
    {
        return [
            [new PurchaseTransaction(), new Started(), new Accepted(), "backend CC=purchase, order state=started, desired state=accepted"],
        ];
    }

    /**
     * @test
     * @dataProvider simple_cases_provider
     */
    public function test_all_simple_combinations(CreditCardTransactionType $creditCardTransactionType, State $currentOrderState, State $expected, $message)
    {
        $shopSystem = new DummyShopSystem($creditCardTransactionType);
        $order = new DummyOrder($currentOrderState);

        $module = new OrderState($shopSystem);
        $newState = $module->getNextState($order);

        $this->assertTrue($expected->equals($newState), $message);
    }

}
