<?php

namespace Test\Double;

use Wirecard\Order\State\Extension\CustomStateFoundation;
use Wirecard\Order\State\State\Pending;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;
use Wirecard\Order\State\State\Success;

/**
 * Class CustomPendingSuccess
 *
 * This class allows a shop system to hook itself into the pending state and insert a new state between pending and
 * success.
 */
class CustomPendingSuccess extends CustomStateFoundation
{

    /**
     * The state in the state machine at which our overriding/hooking starts.
     *
     * @var \Wirecard\Order\State\State\Pending
     */
    private $referenceHead;

    /**
     * The state in the state machine at which our hooking stops.
     *
     * @var Success
     */
    private $referenceTail;

    /**
     * @var CustomStateBetweenPendingAndSuccess
     */
    private $replacement;

    public function __construct()
    {
        parent::__construct();
        $this->referenceHead = new Pending();
        $this->referenceTail = new Success();
        $this->replacement = new CustomStateBetweenPendingAndSuccess();
    }

    public function equals(State $other)
    {
        if ($other->equals($this->referenceHead)) {
            return true;
        }
        return false;
    }

    public function getPossibleNextStates()
    {
        $referenceNext = $this->referenceHead->getPossibleNextStates();
        $referenceNext = $this->replaceState($referenceNext, $this->referenceTail, $this->replacement);
        return $referenceNext;
    }

    public function getNextState(TransitionData $transitionData)
    {
        $referenceNext = $this->referenceHead->getNextState($transitionData);
        if ($this->referenceTail->equals($referenceNext)) {
            return $this->replacement;
        }
        return $referenceNext;
    }
}
