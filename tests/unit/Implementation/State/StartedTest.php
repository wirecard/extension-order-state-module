<?php


use Wirecard\Order\State\State\Failed;
use Wirecard\Order\State\State\Started;
use Wirecard\Order\State\State\Success;

class StartedTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     *
     * Tests that the basic infrastructure for comparing value objects works.
     */
    public function equality()
    {
        $started1 = new Started();
        $started2 = new Started();
        $this->assertTrue($started1->equals($started2));
    }

    /**
     * @test
     *
     * Tests the reverse of the above test (equality).
     */
    public function inequality()
    {
        $success = new Success();
        $failure = new Failed();
        $this->assertFalse($success->equals($failure));
    }
}
