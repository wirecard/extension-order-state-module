<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Registry;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;

/**
 * Class OrderStateRegistry
 * @package Wirecard\ExtensionOrderStateModule\Domain\Registry
 *
 * @method OrderState get(string $key)
 * @since 1.0.0
 */
class OrderStateDataRegistry extends AbstractDataRegistry
{
    /**
     * @var OrderStateDataRegistry
     */
    protected static $instance;

    /**
     * @return OrderStateDataRegistry
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    protected function init()
    {
        foreach (Constant::getOrderStates() as $key) {
            $this->attach($key, new OrderState($key));
        }
    }
}
