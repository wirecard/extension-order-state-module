<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Factories;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType\Notification;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType\Returned;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment\NotificationOrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment\ReturnOrderStateManager;

class OrderStateManagerFactory
{
    /**
     * @var InputDataTransferObject
     */
    private $input;

    public function __construct(InputDataTransferObject $input)
    {
        $this->input = $input;
    }

    /**
     * @return NotificationOrderStateManager|ReturnOrderStateManager
     * @throws InvalidValueException
     */
    public function create()
    {
        $processType = (new ProcessTypeFactory())->create($this->input->getProcessType());

        switch ($processType) {
            case $processType->equalsTo(new Notification()):
                return new NotificationOrderStateManager();
            case $processType->equalsTo(new Returned()):
                return new ReturnOrderStateManager();
            default:
                throw new InvalidValueException("Invalid process type: {$processType}");
        }
    }
}
