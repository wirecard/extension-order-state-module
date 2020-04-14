<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCases;

use Wirecard\ExtensionOrderStateModule\Domain\Factories\OrderStateFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Factories\TransactionStateFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Factories\TransactionTypeFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;

/**
 * Class InputAdapterDTO
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\UseCases
 */
class InputAdapterDTO
{
    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState\TransactionStateValueObject
     */
    private $transactionState;
    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\TransactionTypeValueObject
     */
    private $transactionType;
    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\OrderStateValueObject
     */
    private $currentOrderState;


    /**
     * InputAdapterDTO constructor.
     * @param InputDataTransferObject $input
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException
     */
    public function __construct(InputDataTransferObject $input)
    {
        $this->transactionType = (new TransactionTypeFactory())->create($input->getTransactionType());
        $this->transactionState = (new TransactionStateFactory())->create($input->getTransactionState());
        $this->currentOrderState = (new OrderStateFactory())->create($input->getCurrentOrderState());
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState\TransactionStateValueObject
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\TransactionTypeValueObject
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\OrderStateValueObject
     */
    public function getCurrentOrderState()
    {
        return $this->currentOrderState;
    }


}
