<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entities;

use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\ValueObject;

/**
 * Class StringValueObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entities
 */
abstract class StringValueObject implements ValueObject
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param ValueObject $other
     * @return bool
     * @since 1.0.0
     */
    public function equalsTo(ValueObject $other)
    {
        return $this instanceof $other && $this->value === $other->value;
    }
}
