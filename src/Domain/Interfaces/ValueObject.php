<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;

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
