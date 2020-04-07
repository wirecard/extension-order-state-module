<?php


namespace Wirecard\Order\State\Implementation;

trait StatefulUnaryValueObject
{
    private $value;

    private static $stateMap = [];

    //TODO: make this final
    public function __construct()
    {
        $class = get_class($this);
        if(!isset(self::$stateMap[$class])) {
            self::$stateMap[$class] = count(self::$stateMap);
        }
        $this->value = self::$stateMap[$class];
    }

    /**
     * @param StatefulUnaryValueObject $other
     * @return bool
     */
    private function strictlyEquals($other)
    {
        return $this->value === $other->value;
    }
}