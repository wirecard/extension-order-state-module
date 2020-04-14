<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

/**
 * Class Started
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState
 */
class Started extends OrderStateValueObject
{
    protected $value = Constant::ORDER_STATE_STARTED;
}
