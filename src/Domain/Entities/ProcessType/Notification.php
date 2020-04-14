<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

/**
 * Class Notification
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType
 */
class Notification extends ProcessTypeValueObject
{
    protected $value = Constant::PROCESS_TYPE_NOTIFICATION;
}
