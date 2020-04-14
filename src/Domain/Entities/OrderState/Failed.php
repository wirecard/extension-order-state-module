<?php

namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

class Failed extends OrderStateValueObject
{
    protected $value = Constant::ORDER_STATE_FAILED;
}
