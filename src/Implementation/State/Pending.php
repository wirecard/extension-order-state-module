<?php


namespace Wirecard\Order\State\Implementation\State;


class Pending
{
    use StateHelper;

    public function __construct()
    {
        $this->value = 3;
    }


}