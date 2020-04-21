<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Exception;

use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;

/**
 * Class MapReferenceNotFound
 *
 * The exception usually occurs when referencing between internal / external states is missing
 * @package Wirecard\ExtensionOrderStateModule\Domain\Exception
 * @since 1.0.0
 */
class MapReferenceNotFound extends OrderStateInvalidArgumentException
{

}
