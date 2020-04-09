<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;

interface InputDataTransferObject
{
    public function getTransactionState();

    public function getTransactionType();

    public function getCurrentOrderState();
}