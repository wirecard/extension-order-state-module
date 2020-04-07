<?php


namespace Wirecard\Order\State\Implementation;

trait StatefulUnaryValueObject
{
    private $value;

    private static $stateMap = [];

    public function __construct()
    {
        $class = get_class($this);
        $cacher = $this->getCacher();
        $this->value = $cacher($class);
    }

    /**
     * @param StatefulUnaryValueObject $other
     * @return bool
     */
    private function strictlyEquals($other)
    {
        $getter = function () {
            return $this->value;
        };
        $getter = \Closure::bind($getter, $other, get_class($other));
        return $this->value === $getter();
    }

    /**
     * @return \Closure
     */
    private function getCacher()
    {
        $cacher = function ($class) {
            if (!isset(self::$stateMap[$class])) {
                self::$stateMap[$class] = count(self::$stateMap) + 1;
            }
            return self::$stateMap[$class];
        };
        $cacher = \Closure::bind($cacher, null, StatefulUnaryValueObject::class);
        return $cacher;
    }
}
