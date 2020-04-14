<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

/**
 * Class Returned
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\ProcessType
 */
class Returned extends ProcessTypeValueObject
{
    protected $value = Constant::PROCESS_TYPE_RETURN;
}
