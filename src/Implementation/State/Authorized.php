<?php


namespace Wirecard\Order\State\Implementation\State;


use Wirecard\Order\State\State;

class Authorized implements State
{
    use StateHelper;

    public function __construct()
    {
        $this->value = 1;
    }

}