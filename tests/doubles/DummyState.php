<?php


use Wirecard\Order\State\Extension\CustomStateFoundation;
use Wirecard\Order\State\Implementation\TransitionData;

class DummyState extends CustomStateFoundation
{

    private $possible;
    private $next;

    public function __construct($possible, $next)
    {
        parent::__construct();
        $this->possible = $possible;
        $this->next = $next;
    }

    public function getPossibleNextStates()
    {
        return $this->possible;
    }

    public function getNextState(TransitionData $transitionData)
    {
        return $this->next;
    }
}