<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;

/**
 * Interface InputDataTransferObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Interfaces
 */
interface InputDataTransferObject
{
    /**
     * @return string
     */
    public function getProcessType();
    /**
     * @return string
     */
    public function getTransactionState();
    /**
     * @return string
     */
    public function getTransactionType();
    /**
     * @return string
     */
    public function getCurrentOrderState();
}
