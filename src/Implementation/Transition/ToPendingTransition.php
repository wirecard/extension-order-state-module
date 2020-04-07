<?php


namespace Wirecard\Order\State\Implementation\Transition;

use Wirecard\Order\State\Implementation\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\TransactionType;
use Wirecard\Order\State\TransactionType\Failure;

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

    /**
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return new Failure();
    }
}
