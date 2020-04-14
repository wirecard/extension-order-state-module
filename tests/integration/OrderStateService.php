<?php

class OrderStateService extends PHPUnit_Framework_TestCase
{
    private $orderStateService;

    public function setUp()
    {
        $mapper = new Mapper();
        $this->orderStateService = new OrderState($mapper);
    }

    /**
     * @dataProvider getTestData
     */
    public function testOrderState($expected, $inputData)
    {
        $this->assertEquals($this->orderStateService->process($inputData), $expected);
    }

    public function getTestData()
    {
        //purchase, authorize, failed
        return array(
            array('expected', 'input_data')
        );
    }
}
