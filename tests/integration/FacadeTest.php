<?php

use Wirecard\Order\State\Implementation\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\PurchaseTransaction;
use Wirecard\Order\State\Implementation\State\Failed;
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
            [
                new PurchaseTransaction(),//back-end is set to purchase
                new TransactionType\None(),//there has been no interaction with the engine yet
                new Started(),//the current state of the order is started
                new Pending(),//the desired new state is pending
                "backend CC=purchase, engine=none, order state=started, desired state=pending"
            ],
            [
                new PurchaseTransaction(),
                new TransactionType\Success(),
                new Pending(), new Success(),
                "backend CC=purchase, engine=success, order state=started, desired state=success"
            ],
            [
                new PurchaseTransaction(),
                new TransactionType\Failure(),//the engine said it's a failure
                new Pending(),
                new Failed(),//so we expect the order to go to failed
                "backend CC=purchase, engine=success, order state=started, desired state=failed"
            ],
        ];
    }

    /**
     * @test Tests what should happen
     * @dataProvider simple_cases_provider
     */
    public function all_simple_combinations(
        CreditCardTransactionType $creditCardTransactionType,//backend setting for CC
        TransactionType $engineTransactionType,//what the engine has said about this so far
        State $currentOrderState,//the current state of the order
        State $expected,//the desired state of the order after the transition
        $message)//message, in case the assertion fails
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
