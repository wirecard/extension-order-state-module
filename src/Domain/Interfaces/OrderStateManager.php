<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;


interface OrderStateManager
{
    public function process(OrderStateMapper $mapper, InputDataTransferObject $input);
}