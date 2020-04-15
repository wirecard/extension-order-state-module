<?php


namespace example;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Authorized;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Pending;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Started;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class SampleOrderStateMapper implements OrderStateMapper
{

    public function map()
    {
        return [
            "started_external" => new Started(),
            "pending_external" => new Pending(),
            "failed_external" => new Failed(),
            "authorized_external" => new Authorized(),
            "processing_external" => new Processing(),
        ];
    }
}
