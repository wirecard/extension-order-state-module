<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;

/**
 * Interface ValueObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Interfaces
 */
interface ValueObject
{
    /**
     * @return mixed
     */
    public function __toString();

    /**
     * @param ValueObject $other
     * @return bool
     */
    public function equalsTo(ValueObject $other);
}
