<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Registry;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;

/**
 * Class OrderStateRegistry
 * @package Wirecard\ExtensionOrderStateModule\Domain\Registry
 *
 * @method static OrderStateDataRegistry getInstance()
 * @method OrderState get(string $key)
 */
class OrderStateDataRegistry extends AbstractDataRegistry
{
    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exceptions\InvalidValueObjectException
     */
    protected function init()
    {
        foreach (Constant::getOrderStates() as $key) {
            $this->attach($key, new OrderState($key));
        }
    }
}
