<?php

namespace implementation;


use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\DefaultInputDTO;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\InputAdapterDTO;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class DefaultMapper implements OrderStateMapper
{

    public function map()
    {
        return [
            "started_external" => "started",
            "pending_external" => "pending",
            "failed_external" => "failed",
        ];
    }
}

$orderStateService = new OrderState(new DefaultMapper());

class PrestashopDTO implements InputDataTransferObject
{

    public function getProcessType()
    {
        return "returned";
    }

    public function getTransactionState()
    {
        return "success";
    }

    public function getTransactionType()
    {
        return "debit";
    }

    public function getCurrentOrderState()
    {
        return "started";
    }
}

$inputDTO = new PrestashopDTO();

$orderStateService->process($inputDTO);
