<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

/**
 * Class Pending
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState
 */
class Pending extends OrderStateValueObject
{
    protected $value = Constant::ORDER_STATE_PENDING;
}
