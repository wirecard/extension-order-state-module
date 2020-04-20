<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Registry;

/**
 * Trait DataRegistry
 * @package Wirecard\ExtensionOrderStateModule\Domain\Registry
 */
trait DataRegistry
{
    /**
     * @param string $state
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @since 1.0.0
     */
    public function fromOrderStateRegistry($state)
    {
        return OrderStateDataRegistry::getInstance()->get($state);
    }

    /**
     * @param string $type
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @since 1.0.0
     */
    public function fromTransactionTypeRegistry($type)
    {
        return TransactionTypeDataRegistry::getInstance()->get($type);
    }
}
