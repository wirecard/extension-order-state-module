<?php


namespace Test\Double\Dummy;


use Test\Double\Stub\StateStub;

class StateDummy extends StateStub
{

    public function __construct()
    {
        parent::__construct(null, null);
    }

}