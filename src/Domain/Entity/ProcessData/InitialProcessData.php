<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;

use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class InitialProcessData
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData
 * @since 1.0.0
 */
class InitialProcessData implements ProcessData
{
    use DataRegistry;

    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     */
    private $orderState;
    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType
     */
    private $transactionType;
    /**
     * @var TransactionState
     */
    private $transactionState;

    /**
     * ProcessData constructor.
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function __construct(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $this->orderState = $mapper->toInternal($input->getCurrentOrderState());
        $this->transactionType = $this->fromTransactionTypeRegistry($input->getTransactionType());
        $this->transactionState = new TransactionState($input->getTransactionState());
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType
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
}
