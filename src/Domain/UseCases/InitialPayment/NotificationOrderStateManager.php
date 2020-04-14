<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class NotificationOrderStateManager implements OrderStateManager
{

    /**
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @return mixed
     */
    public function process(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        // TODO: Implement process() method.
    }
}
