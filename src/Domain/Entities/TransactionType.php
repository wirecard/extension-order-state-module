<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entities;

/**
 * Class TransactionType
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities
 */
class TransactionType extends ConstantStringValueObject
{
    /**
     * @return array
     * @since 1.0.0
     */
    public function possibleValueSet()
    {
        return Constant::getTransactionTypes();
    }
}
