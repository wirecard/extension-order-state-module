<?php


namespace Wirecard\Order\State\Implementation\Transition;

use Wirecard\Order\State\Implementation\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\TransitionData;

class ToPendingTransition implements TransitionData
{

    /**
     * @var CreditCardTransactionType
     */
    private $creditCardTransactionType;

    public function __construct(CreditCardTransactionType $creditCardTransactionType)
    {
        $this->creditCardTransactionType = $creditCardTransactionType;
    }

    /**
     * @return CreditCardTransactionType
     */
    public function getShopsystemCreditCardTransactionType()
    {
        return $this->creditCardTransactionType;
    }
}
