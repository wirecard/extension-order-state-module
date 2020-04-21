<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity;

use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException;

/**
 * Class EnumValueObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity
 * @since 1.0.0
 */
abstract class EnumValueObject extends StringValueObject
{
    /**
     * StringSetValueObject constructor.
     * @param string $value
     * @throws InvalidValueObjectException
     */
    public function __construct($value)
    {
        $this->guard($value);
        $this->value = $value;
    }

    /**
     * @param string $value
     * @throws InvalidValueObjectException
     */
    protected function guard($value)
    {
        if (!in_array($value, $this->possibleValueSet(), true)) {
            throw new InvalidValueObjectException(self::class .": Invalid value {$value}!");
        }
    }

    /**
     * @return array
     */
    abstract public function possibleValueSet();
}
