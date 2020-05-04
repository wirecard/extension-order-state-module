<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Example;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\TransactionDataTransferObject;

/**
 * Sample implementation of Input contract for handling order states
 * Class SampleInputDTO
 * @package Wirecard\ExtensionOrderStateModule\Example
 * @since 1.0.0
 */
class SampleInputDTO implements InputDataTransferObject
{
    /**
     * @var string
     */
    private $processType;

    /**
     * @var string
     */
    private $currentOrderState;

    /**
     * @var string
     */
    private $transactionType;

    /**
     * @var string
     */
    private $transactionState;

    /**
     * @var float
     */
    private $orderOpenAmount = 0;

    /**
     * @var float
     */
    private $transactionRequestedAmount = 0;

    /**
     * @var array
     */
    private $transactionList;

    /**
     * @return string
     */
    public function getProcessType()
    {
        return $this->processType;
    }

    /**
     * @param string $processType
     */
    public function setProcessType($processType)
    {
        $this->processType = $processType;
    }

    /**
     * @return string
     */
    public function getCurrentOrderState()
    {
        return $this->currentOrderState;
    }

    /**
     * @param string $currentOrderState
     */
    public function setCurrentOrderState($currentOrderState)
    {
        $this->currentOrderState = $currentOrderState;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param string $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return string
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }

    /**
     * @param string $transactionState
     */
    public function setTransactionState($transactionState)
    {
        $this->transactionState = $transactionState;
    }

    /**
     * @return array
     */
    public function getTransactions()
    {
        return $this->transactionList;
    }

    public function addTransaction(TransactionDataTransferObject $transaction)
    {
        $this->transactionList[] = $transaction;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%s_%s_%s_%s",
            $this->transactionType,
            $this->currentOrderState,
            $this->transactionState,
            $this->processType
        );
    }

    /**
     * @return float
     */
    public function getOrderOpenAmount()
    {
        return $this->orderOpenAmount;
    }

    /**
     * @param float $orderOpenAmount
     */
    public function setOrderOpenAmount($orderOpenAmount)
    {
        $this->orderOpenAmount = $orderOpenAmount;
    }

    /**
     * @return float
     */
    public function getTransactionRequestedAmount()
    {
        return $this->transactionRequestedAmount;
    }

    /**
     * @param float $transactionRequestedAmount
     */
    public function setTransactionRequestedAmount($transactionRequestedAmount)
    {
        $this->transactionRequestedAmount = $transactionRequestedAmount;
    }

    /**
     * State Flow (Notification): Failed => PartialCaptured => Processing => PartialRefunded => Refunded
     *
     * Scenario 1:
     * order amount:100; orderstate: authorized; tr.type:capture-authorization;req.am:100; => processing
     * order amount:100; orderstate: processing; tr.type:refund-capture;req.am:100; => refunded
     *
     * Scenario 2:
     * order amount:100; orderstate: authorized; tr.type:capture-authorization;req.am:80; => partial-captured
     * order amount:20; orderstate: partial-captured; tr.type:capture-authorization;req.am:20; => processing
     *
     * Scenario 3:
     * order amount:100; orderstate: authorized; tr.type:capture-authorization;req.am:100; => processing
     * order amount:100; orderstate: processing; tr.type:refund-capture;req.am:30; => partial refunded
     * order amount:70; orderstate: partial refunded; tr.type:refund-capture;req.am:40; => partial refunded
     * order amount:30; orderstate: partial refunded; tr.type:refund-capture;req.am:30; => refunded
     *
     *
     * Scenario 4:
     * order amount:100; orderstate: authorized; tr.type:capture-authorization;req.am:30; => partial captured
     * order amount:70; orderstate:  partial captured; tr.type:capture-authorization;req.am:40; => partial capture
     * order amount:30; orderstate:  partial captured; tr.type:capture-authorization;req.am:30; => processing
     *
     * order amount:100; orderstate: processing; tr.type:refund-capture;req.am:30; => partial refunded
     * order amount:70; orderstate: partial refunded; tr.type:refund-capture;req.am:40; => partial refunded
     * order amount:30; orderstate: partial refunded; tr.type:refund-capture;req.am:30; => refunded
     *
     * Scenario 5:
     * order amount:100; orderstate: authorized; tr.type:void-authorization;req.am:100; => cancelled
     *
     * Scenario 6:
     * order amount:100; orderstate: authorized; tr.type:capture-authorization;req.am:30; => partial captured
     * order amount:70; orderstate: partial captured; tr.type:refund-capture;req.am:20; => partial captured
     * order amount:70; orderstate: partial captured; tr.type:refund-capture;req.am:10; => partial refunded
     *
     *
     *
     * OrderAmount: 100
     *
     * CAPTURING_GROUP
     * Cap1: 30
     * REFUNDING_GROUP
     * Ref1:-20
     * Ref2: -10
     *                                           +                -
     * REMAIN_ORDER_TOTAL = OrderTotal - (CAPTURING_GROUP + REFUNDING_GROUP)
     *
     */

}
