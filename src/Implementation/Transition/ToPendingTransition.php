<?php


namespace Wirecard\Order\State\Implementation\Transition;

use Wirecard\Order\State\Implementation\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\TransactionType;

/**
 * Class ToPendingTransition
 * @package Wirecard\Order\State\Implementation\Transition
 *
 * @todo: move this out of directory and rename
 */
class ToPendingTransition implements TransitionData
{

    /**
     * @var CreditCardTransactionType
     */
    private $creditCardTransactionType;

    /**
     * @var TransactionType
     */
    private $transactionType;

    public function __construct(CreditCardTransactionType $creditCardTransactionType, TransactionType $transactionType)
    {
        $this->creditCardTransactionType = $creditCardTransactionType;
        $this->transactionType = $transactionType;
    }

    /**
     * @return CreditCardTransactionType
     */
    public function getShopsystemCreditCardTransactionType()
    {
        return $this->creditCardTransactionType;
    }

    /**
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }
}
