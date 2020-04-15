<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\StringValueObject;

/**
 * Class OrderStateValueObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState
 */
abstract class OrderStateValueObject extends StringValueObject
{
    /**
     * @return bool
     */
    public function isStarted()
    {
        return $this->equalsTo(new Started());
    }
}
