<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

/**
 * Class Processing
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState
 */
class Processing extends OrderStateValueObject
{
    protected $value = Constant::ORDER_STATE_PROCESSING;
}
