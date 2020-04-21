<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Exception;

/**
 * Class InvalidValueObjectException
 *
 * The exception usually occurs if value of EnumValueObject classes group is invalid or not implemented.
 *
 * @see \Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant
 * @see \Wirecard\ExtensionOrderStateModule\Domain\Entity\EnumValueObject
 * @package Wirecard\ExtensionOrderStateModule\Domain\Exception
 * @since 1.0.0
 */
class InvalidValueObjectException extends OrderStateInvalidArgumentException
{

}
