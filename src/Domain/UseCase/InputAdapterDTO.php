<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;

/**
 * Class InputAdapterDTO
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity\UseCase
 */
class InputAdapterDTO
{
    /**
     * @var TransactionState
     */
    private $transactionState;
    /**
     * @var TransactionType
     */
    private $transactionType;
    /**
     * @var OrderState
     */
    private $orderState;

    /**
     * InputAdapterDTO constructor.
     * @param InputDataTransferObject $input
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @since 1.0.0
     */
    public function __construct(InputDataTransferObject $input)
    {
        $this->transactionType = new TransactionType($input->getTransactionType());
        $this->transactionState = new TransactionState($input->getTransactionState());
        $this->orderState = new OrderState($input->getCurrentOrderState());
    }

    /**
     * @return OrderState
     * @since 1.0.0
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * @return TransactionType
     * @since 1.0.0
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return TransactionState
     * @since 1.0.0
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }
}
