<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\Implementation\State\Started;
use Wirecard\Order\State\Implementation\State\StateHelper;

class StateRegistry
{
    private $states = [];
    private $transitions = [];

    public function __construct()
    {
        $currentState = new Started();
        $currentValue = $this->getStateValue($currentState);
    }

    private function getStateValue(\Wirecard\Order\State\State $state)
    {
        $getValue = function () {
            /** @var  StateHelper $this */
            return $this->value;
        };
    }
}
