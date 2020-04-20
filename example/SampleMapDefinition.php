<?php


namespace example;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MapDefinition;

class SampleMapDefinition implements MapDefinition
{

    /**
     * @return array
     * @since 1.0.0
     */
    public function map()
    {
        return [
            "started_external" => Constant::ORDER_STATE_STARTED,
            "pending_external" => Constant::ORDER_STATE_PENDING,
            "failed_external" => Constant::ORDER_STATE_FAILED,
            "authorized_external" => Constant::ORDER_STATE_AUTHORIZED,
            "processing_external" => Constant::ORDER_STATE_PROCESSING,
        ];
    }
}
