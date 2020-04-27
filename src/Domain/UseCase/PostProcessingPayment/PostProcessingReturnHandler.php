<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment;

use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturn\Failed;

/**
 * Class PostProcessingReturnHandler
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment
 * @since 1.0.0
 */
class PostProcessingReturnHandler extends AbstractProcessHandler
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        $orderState = parent::handle();
        if (null === $orderState) {
            $orderState = $this->processData->getOrderState();
        }
        return $orderState;
    }

    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Failed($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        return null;
    }
}
