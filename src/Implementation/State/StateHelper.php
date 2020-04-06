<?php


namespace Wirecard\Order\State\Implementation\State;

use Wirecard\Order\State\State;

trait StateHelper
{
    private $value;

    private static $stateMap = [];

    public function __construct()
    {
        $class = get_class($this);
        if(!isset(self::$stateMap[$class])) {
            self::$stateMap[$class] = count(self::$stateMap);
        }
        $this->value = self::$stateMap[$class];
    }

    public function equals(State $other)
    {
        return $this->isStrictlyEqual($other);
    }

    /**
     * @param $other StateHelper
     * @return bool
     */
    private function isStrictlyEqual($other)
    {
        return $this->value === $other->value;
    }
}
