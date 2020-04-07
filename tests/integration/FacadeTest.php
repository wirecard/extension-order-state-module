<?php

use Wirecard\Order\State\Implementation\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\PurchaseTransaction;
use Wirecard\Order\State\Implementation\State\Pending;
use Wirecard\Order\State\Implementation\State\Started;
use Wirecard\Order\State\Implementation\State\Success;
use Wirecard\Order\State\OrderState;
use Wirecard\Order\State\State;
use Wirecard\Order\State\TransactionType;


class FacadeTest extends PHPUnit_Framework_TestCase
{
    public function simple_cases_provider()
    {
        return [
            [new PurchaseTransaction(), new TransactionType\None(), new Started(), new Pending(), "backend CC=purchase, engine=none, order state=started, desired state=pending"],
            [new PurchaseTransaction(), new TransactionType\Success(), new Pending(), new Success(), "backend CC=purchase, engine=success, order state=started, desired state=success"],
        ];
    }

    /**
     * @test Tests what should happen
     * @dataProvider simple_cases_provider
     */
    public function all_simple_combinations(CreditCardTransactionType $creditCardTransactionType, TransactionType $engineTransactionType, State $currentOrderState, State $expected, $message)
    {
        $shopSystem = new DummyShopSystem($creditCardTransactionType);
        $order = new DummyOrder($currentOrderState, $engineTransactionType);

        $module = new OrderState($shopSystem);
        $newState = $module->getNextState($order);

        $this->assertTrue($expected->equals($newState), $message . ' Got instead: '.get_class($newState));
    }

    public function reversed_simple_cases_provider()
    {
        return [

        ];
    }

    /**
     * Tests what should not happend
     * @dataProvider reversed_simple_cases_provider
     * @todo depends on the StateRegistry
     */
    public function reverse_simple_combinations()
    {

    }

}
