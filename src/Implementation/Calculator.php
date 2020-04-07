<?php


namespace Wirecard\Order\State\Implementation;

use RuntimeException;
use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\State;
use Wirecard\Order\State\TransactionType;

/**
 * Class Calculator
 * @package Wirecard\Order\State\Implementation
 */
class Calculator
{

    /**
     * @var CreditCardTransactionType
     */
    private $ccType;

    /**
     * @var CalculableState
     */
    private $currentState;

    public function __construct(CreditCardTransactionType $ccType, CalculableState $currentState)
    {
        $this->ccType = $ccType;
        $this->currentState = $currentState;
    }

    public function calculate(TransactionType $transactionType)
    {
        $transitionData = new Transition($this->ccType, $transactionType);
        $nextState = $this->currentState->getNextState($transitionData);
        $this->checkConstraints($nextState);
        return $nextState;
    }

    /**
     * @param State $nextState
     */
    private function checkConstraints(State $nextState)
    {
        if (!$this->isValidObject($nextState)) {
            $nextStateName = (string)$nextState;
            $message = "Invalid state: $nextStateName. It must implement " . CalculableState::class;
            throw new RuntimeException($message);
        }
        $currentStateName = get_class($this->currentState);
        $candidates = $this->currentState->getPossibleNextStates();
        if (!is_array($candidates)) {
            $message = "Current state $currentStateName did not provide a list of possible next states";
            throw new RuntimeException($message);
        }
        $isPossible = $this->isPossible($nextState, $candidates);
        if (!$isPossible) {
            $nextStateName = get_class($nextState);
            $message = "Calculated next state $nextStateName is not declared as possible by $currentStateName";
            throw new RuntimeException($message);
        }
    }

    /**
     * @param State $nextState
     * @return bool
     */
    private function isValidObject(State $nextState)
    {
        return is_object($nextState) && ($nextState instanceof CalculableState);
    }

    /**
     * @param State $nextState
     * @param $candidates
     * @return bool
     */
    private function isPossible(State $nextState, $candidates)
    {
        $isPossible = false;
        foreach ($candidates as $candidate) {
            if ($nextState->equals($candidate)) {
                $isPossible = true;
                break;
            }
        }
        return $isPossible;
    }
}
