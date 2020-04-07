<?php

use Wirecard\Order\State\CustomStateFoundation;
use Wirecard\Order\State\Implementation\State\Success;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

class CustomStateBetweenPendingAndSuccess extends CustomStateFoundation
{

    /**
     * @var Success
     */
    private $referenceTail;

    public function __construct()
    {
        parent::__construct();
        $this->referenceTail = new Success();
    }

    public function getPossibleNextStates()
    {
        return [$this->referenceTail];
    }

    public function getNextState(TransitionData $transitionData)
    {
        return $this->referenceTail;
    }

    public function equals(State $other)
    {
        if ($other->equals($this->referenceTail)) {
            return true;
        }
        return false;
    }
}