<?php


class FacadeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function payment_always_failing()
    {
        $shopSystem = new AlwaysFailingShopSystem();
        $order = new MallorysFirstOrder();
        $module = new \Wirecard\Order\State\OrderState($shopSystem);
        $newState = $module->getNextState($order);
        $expected = new \Wirecard\Order\State\Implementation\State\Failed();
        $this->assertTrue($expected->equals($newState));
    }

}
