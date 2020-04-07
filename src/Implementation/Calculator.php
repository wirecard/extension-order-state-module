<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\Transition\ToPendingTransition;
use Wirecard\Order\State\State;
use Wirecard\Order\State\TransactionType;

class Calculator
{

    /**
     * @var CreditCardTransactionType
     */
    private $ccType;
    /**
     * @var \Wirecard\Order\State\Extension\CalculableState
     */
    private $currentState;

    public function __construct(CreditCardTransactionType $ccType, CalculableState $currentState)
    {
        $this->ccType = $ccType;
        $this->currentState = $currentState;
    }

    public function calculate(TransactionType $transactionType)
    {
        $transitionData = new ToPendingTransition($this->ccType, $transactionType);
        $nextState = $this->currentState->getNextState($transitionData);
        $this->checkConstraints($nextState);
        return $nextState;
    }

    /**
     * @param State $nextState
     */
    private function checkConstraints(State $nextState)
    {
        if (!is_object($nextState) || !($nextState instanceof \Wirecard\Order\State\Extension\CalculableState)) {
            throw new \RuntimeException("Invalid next state: " . ((string)$nextState) . ". It must implement " . CalculableState::class);
        }
        $currentStateName = get_class($this->currentState);
        $candidates = $this->currentState->getPossibleNextStates();
        if (!is_array($candidates)) {
            throw new \RuntimeException("Current state $currentStateName did not provide a list of possible next states");
        }
        $isPossible = false;
        foreach ($candidates as $candidate) {
            if ($nextState->equals($candidate)) {
                $isPossible = true;
                break;
            }
        }
        if (!$isPossible) {
            $nextStateName = get_class($nextState);

            throw new \RuntimeException("Calculated next state $nextStateName is not declared as possible by $currentStateName");
        }
    }
}
