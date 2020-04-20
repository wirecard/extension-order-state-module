<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ValueObject;

class ProcessData implements ValueObject
{
    /**
     * @var OrderState
     */
    private $orderState;
    /**
     * @var TransactionType
     */
    private $transactionType;
    /**
     * @var TransactionState
     */
    private $transactionState;

    /**
     * ProcessData constructor.
     * @param OrderState $orderState
     * @param TransactionType $transactionType
     * @param TransactionState $transactionState
     */
    public function __construct(
        OrderState $orderState,
        TransactionType $transactionType,
        TransactionState $transactionState
    ) {
        $this->orderState = $orderState;
        $this->transactionType = $transactionType;
        $this->transactionState = $transactionState;
    }

    /**
     * @return OrderState
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return TransactionState
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return "{$this->orderState}_{$this->transactionType}_{$this->transactionState}";
    }

    /**
     * @param ValueObject|ProcessData $other
     * @return bool
     */
    public function equalsTo(ValueObject $other)
    {
        return $this instanceof $other &&
            $other->orderState->equalsTo($this->orderState) &&
            $other->transactionType->equalsTo($this->transactionType) &&
            $other->transactionState->equalsTo($this->transactionState);
    }
}
