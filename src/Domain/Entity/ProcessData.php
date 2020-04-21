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
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class ProcessData
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity
 * @since 1.0.0
 */
class ProcessData implements ValueObject
{
    use DataRegistry;

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
     * @param string $state
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function orderInState($state)
    {
        return $this->orderState->equalsTo($this->fromOrderStateRegistry($state));
    }

    /**
     * @param string $type
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function transactionInType($type)
    {
        return $this->transactionType->equalsTo($this->fromTransactionTypeRegistry($type));
    }

    /**
     * @param array $typeRange
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function transactionTypeInRange(array $typeRange)
    {
        return $this->isTransactionTypeInRange($this->transactionType, $typeRange);
    }

    /**
     * @param string $state
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function transactionInState($state)
    {
        return $this->transactionState->equalsTo(new TransactionState($state));
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
