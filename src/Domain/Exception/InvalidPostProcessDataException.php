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
 * Class InvalidPostProcessDataException
 *
 * The exception usually occurs if some needed data missed or invalid.
 *
 * @see \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData
 * @package Wirecard\ExtensionOrderStateModule\Domain\Exception
 * @since 1.0.0
 */
class InvalidPostProcessDataException extends OrderStateInvalidArgumentException
{

}
