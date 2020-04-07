<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\Implementation\State\CalculableState;
use Wirecard\Order\State\Implementation\Transition\ToPendingTransition;
use Wirecard\Order\State\TransactionType;

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
        $transitionData = new ToPendingTransition($this->ccType, $transactionType);
        $nextState = $this->currentState->getNextState($transitionData);
        if (!is_object($nextState) || !($nextState instanceof CalculableState)) {
            throw new \RuntimeException("Invalid next state: " . ((string)$nextState) . ". It must implement " . CalculableState::class);
        }
        $candidates = $this->currentState->getPossibleNextStates();
        $isPossible = false;
        foreach ($candidates as $candidate) {
            if ($nextState->equals($candidate)) {
                $isPossible = true;
                break;
            }
        }
        if (!$isPossible) {
            $nextStateName = get_class($nextState);
            $currentStateName = get_class($this->currentState);
            throw new \RuntimeException("Calculated next state $nextStateName is not declared as possible by $currentStateName");
        }
        return $nextState;
    }
}
