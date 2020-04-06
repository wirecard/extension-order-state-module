<?php


namespace Wirecard\Order\State\Implementation\State;

class Processing
{
    use StateHelper;

    public function __construct()
    {
        $this->value = 4;
    }
}
