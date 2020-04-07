<?php


namespace Wirecard\Order\State\Implementation;

use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\TransactionType;

/**
 * Class Transition
 *
 * @package Wirecard\Order\State\Implementation
 */
class Transition implements TransitionData
{

    /**
     * @var \Wirecard\Order\State\CreditCardTransactionType
     */
    private $transactionSetting;

    /**
     * @var TransactionType
     */
    private $transactionType;

    public function __construct(CreditCardTransactionType $transactionSetting, TransactionType $transactionType)
    {
        $this->transactionSetting = $transactionSetting;
        $this->transactionType = $transactionType;
    }

    /**
     * @return CreditCardTransactionType
     */
    public function getShopsystemCreditCardTransactionType()
    {
        return $this->transactionSetting;
    }

    /**
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }
}
