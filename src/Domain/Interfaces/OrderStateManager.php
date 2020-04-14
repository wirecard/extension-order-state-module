<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;

interface OrderStateManager
{

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return mixed
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper);
}
