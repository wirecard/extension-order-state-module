<?php


use Test\Double\Stub\OrderStateStub;
use Wirecard\Order\State\CreditCardTransactionType\PurchaseTransaction;
use Wirecard\Order\State\TransactionType\None;

class CalculatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     *
     * The calculator should fail when returning null as a next state.
     * @expectedException Exception
     */
    public function does_not_allow_null()
    {
        $ccType = new PurchaseTransaction();
        $currentState = new OrderStateStub([], null);
        $calculator = new \Wirecard\Order\State\Implementation\Calculator($ccType, $currentState);
        $calculator->calculate(new None());
    }

    /**
     * @test
     *
     * The calculator should fail when returning null as a next state.
     * @expectedException Exception
     */
    public function does_not_allow_empty_array()
    {
        $ccType = new PurchaseTransaction();
        $currentState = new OrderStateStub([], new OrderStateStub(null, null));
        $calculator = new \Wirecard\Order\State\Implementation\Calculator($ccType, $currentState);
        $calculator->calculate(new None());
    }

    /**
     * @test
     *
     * The calculator should fail when returning null as a next state.
     * @expectedException Exception
     */
    public function does_not_allow_non_array_next()
    {
        $ccType = new PurchaseTransaction();
        $currentState = new OrderStateStub(null, new OrderStateStub(null, null));
        $calculator = new \Wirecard\Order\State\Implementation\Calculator($ccType, $currentState);
        $calculator->calculate(new None());
    }
}
