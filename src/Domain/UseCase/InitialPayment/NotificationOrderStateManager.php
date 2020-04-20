<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;

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
