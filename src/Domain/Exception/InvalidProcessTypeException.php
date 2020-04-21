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
 * Class InvalidProcessTypeException
 *
 * The exception occurs if process type is invalid or not implemented
 *
 * @see \Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant -> PROCESS_TYPE_*
 * @package Wirecard\ExtensionOrderStateModule\Domain\Exception
 * @since 1.0.0
 */
class InvalidProcessTypeException extends OrderStateInvalidArgumentException
{

}
