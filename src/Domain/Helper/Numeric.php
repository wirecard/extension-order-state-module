<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Helper;

/**
 * Trait Numeric
 * @package Wirecard\ExtensionOrderStateModule\Domain\Helper
 * @since 1.0.0
 */
trait Numeric
{
    /**
     * Decides whether two float numbers are equal, given a precision.
     * @param $firstNumber
     * @param $secondNumber
     * @param null|int $precision
     * @return bool
     *
     * No validation is done here, because it's a private method. The class using it has more context to decide what
     * kind of validation is necessary.
     */
    private function equals($firstNumber, $secondNumber, $precision)
    {
        $integerCoefficient = pow(10, $precision);
        $fractionalCoefficient = pow(10, -1 * $precision);
        $threshold = $integerCoefficient * $fractionalCoefficient;
        $firstNumber *= $integerCoefficient;
        $secondNumber *= $integerCoefficient;
        $difference = abs($firstNumber - $secondNumber);
        return $difference < $threshold;
    }

    /**
     * @param float $firstNumber
     * @param float $secondNumber
     * @param null $precision If null, use prestashop's default
     * @return float
     *
     * Work with integers instead of floats, which makes rounding a safe operation, and thus the final division.
     */
    private function difference($firstNumber, $secondNumber, $precision)
    {
        $integerCoefficient = pow(10, $precision);
        $firstNumber *= $integerCoefficient;
        $secondNumber *= $integerCoefficient;
        $diff = round($firstNumber - $secondNumber);
        return $diff / $integerCoefficient;
    }
}
