<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Example;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;

/**
 * Class SampleMappingDefinition
 *
 * Implementation for MappingDefinition interface. Used to define
 * mapping between internal and external order state definitions
 *
 * @package Wirecard\ExtensionOrderStateModule\Example
 * @since 1.0.0
 */
class SampleMappingDefinition implements MappingDefinition
{

    /**
     * @return array
     */
    public function definitions()
    {
        return [
            "started_external" => Constant::ORDER_STATE_STARTED,
            "pending_external" => Constant::ORDER_STATE_PENDING,
            "failed_external" => Constant::ORDER_STATE_FAILED,
            "authorized_external" => Constant::ORDER_STATE_AUTHORIZED,
            "processing_external" => Constant::ORDER_STATE_PROCESSING,
            "refunded_external" => Constant::ORDER_STATE_REFUNDED,
            "partial_refunded_external" => Constant::ORDER_STATE_PARTIAL_REFUNDED,
            "partial_captured_external" => Constant::ORDER_STATE_PARTIAL_CAPTURED,
            "cancelled_external" => Constant::ORDER_STATE_CANCELLED,
        ];
    }
}
