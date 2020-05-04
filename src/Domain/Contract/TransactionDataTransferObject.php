<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Contract;


interface TransactionDataTransferObject
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return float
     */
    public function getAmount();
}
