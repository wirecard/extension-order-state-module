<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment;


use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class ReturnOrderStateManager implements OrderStateManager
{
    public function process(OrderStateMapper $mapper, InputDataTransferObject $input)
    {
        // TODO: Implement process() method.
    }

}