<?php

use Test\Double\CustomPendingSuccess;
use Test\Double\CustomStateBetweenPendingAndSuccess;
use Test\Double\Stub\CustomShopSystemStub;
use Test\Double\Stub\OrderStub;
use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\CreditCardTransactionType\PurchaseTransaction;
use Wirecard\Order\State\State\Success;
use Wirecard\Order\State\OrderState;
use Wirecard\Order\State\State;
use Wirecard\Order\State\TransactionType;

class CustomStateTest extends PHPUnit_Framework_TestCase
{
    public function simple_cases_provider()
    {
        return [
            [
                new PurchaseTransaction(),
                new TransactionType\Success(),
                new CustomPendingSuccess(),
                new CustomStateBetweenPendingAndSuccess(),
                "backend CC=purchase, engine=none, order state=started, desired state=pending"
            ],
            [
                new PurchaseTransaction(),
                new TransactionType\Success(),
                new CustomStateBetweenPendingAndSuccess(),
                new Success(),
                "backend CC=purchase, engine=none, order state=started, desired state=pending"
            ],
        ];
    }

    /**
     * @test Tests what should happen
     * @dataProvider simple_cases_provider
     * @param CreditCardTransactionType $creditCardTransactionType
     * @param TransactionType $engineTransactionType
     * @param State $currentOrderState
     * @param State $expected
     * @param $message
     */
    public function all_simple_combinations(
        CreditCardTransactionType $creditCardTransactionType,//backend setting for CC
        TransactionType $engineTransactionType,//what the engine has said about this so far
        State $currentOrderState,//the current state of the order
        State $expected,//the desired state of the order after the transition
        $message
    ) {//message, in case the assertion fails
        $shopSystem = new CustomShopSystemStub($creditCardTransactionType);
        $order = new OrderStub($currentOrderState, $engineTransactionType);

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
