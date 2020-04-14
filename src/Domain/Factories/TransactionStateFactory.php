<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Factories;


use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState\Failure;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState\Success;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;

class TransactionStateFactory
{
    /**
     * @param $transactionState
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionState\TransactionStateValueObject
     * @throws InvalidValueException
     */
    public function create($transactionState)
    {
        switch ($transactionState) {
            case Constant::TRANSACTION_STATE_SUCCESS:
                return new Success();
            case Constant::TRANSACTION_STATE_FAILURE:
                return new Failure();
            default:
                throw new InvalidValueException(
                    "Transaction state {$transactionState} is invalid or not implemented process type"
                );
        }
    }
}
