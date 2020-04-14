<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Factories;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\Authorize;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\Debit;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\Purchase;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;

class TransactionTypeFactory
{


    /**
     * @param $transactionType
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entities\TransactionType\TransactionTypeValueObject
     * @throws InvalidValueException
     */
    public function create($transactionType)
    {
        switch ($transactionType) {
            case Constant::TRANSACTION_TYPE_AUTHORIZE:
                return new Authorize();
            case Constant::TRANSACTION_TYPE_DEBIT:
                return new Debit();
            case Constant::TRANSACTION_TYPE_PURCHASE:
                return new Purchase();
            default:
                throw new InvalidValueException(
                    "Transaction type [{$transactionType}] is invalid or not implemented process type"
                );
        }
    }
}
