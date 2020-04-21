<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Registry;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;

/**
 * Trait DataRegistry
 *
 * DataRegistry provides wrappers to registry classes.
 * @see OrderStateDataRegistry
 * @see TransactionTypeDataRegistry
 *
 * @package Wirecard\ExtensionOrderStateModule\Domain\Registry
 * @since 1.0.0
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

    /**
     * @param TransactionType $needed
     * @param array $range
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function isTransactionTypeInRange(TransactionType $needed, array $range)
    {
        foreach ($range as $value) {
            if ($needed->equalsTo($this->fromTransactionTypeRegistry($value))) {
                return true;
            }
        }
        return false;
    }
}
