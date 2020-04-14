<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Factories;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType\Notification;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType\ProcessTypeValueObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType\Returned;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;

class ProcessTypeFactory
{

    /**
     * @param $processType
     * @return ProcessTypeValueObject
     * @throws InvalidValueException
     */
    public function create($processType)
    {
        switch ($processType) {
            case Constant::PROCESS_TYPE_RETURN:
                return new Returned();
            case Constant::PROCESS_TYPE_NOTIFICATION:
                return new Notification();
            default:
                throw new InvalidValueException("Invalid or not implemented process type {$processType}");
        }
    }

}
