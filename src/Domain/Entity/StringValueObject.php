<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ValueObject;

/**
 * Class StringValueObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity
 * @since 1.0.0
 */
abstract class StringValueObject implements ValueObject
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @param ValueObject $other
     * @return bool
     */
    public function equalsTo(ValueObject $other)
    {
        return $this instanceof $other && $this->value === $other->value;
    }
}
