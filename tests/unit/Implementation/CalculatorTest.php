<?php


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
        $currentState = new DummyState([], null);
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
        $currentState = new DummyState([], new DummyState(null, null));
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
        $currentState = new DummyState(null, new DummyState(null, null));
        $calculator = new \Wirecard\Order\State\Implementation\Calculator($ccType, $currentState);
        $calculator->calculate(new None());
    }
}
