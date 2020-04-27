<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity;

/**
 * Class OrderState
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity
 * @since 1.0.0
 */
class OrderState extends EnumValueObject
{
    /**
     * @return array
     */
    public function possibleValueSet()
    {
        return Constant::getOrderStates();
    }
}
