<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities;

use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\ValueObject;

class StringValueObject implements ValueObject
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
        return (string)$this->value;
    }

    /**
     * @param ValueObject $other
     * @return bool
     */
    public function equalsTo(ValueObject $other)
    {
        return $this instanceof $other && $this->value === $other->value;
    }

    /**
     * @param array $valueObjects
     * @return bool
     */
    public function inSet(array $valueObjects)
    {
        $result = false;
        foreach ($valueObjects as $valueObject) {
            if ($this->equalsTo($valueObject)) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}
