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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotification\Failed;

/**
 * Class PostProcessingNotifxicationHandler
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment
 * @since 1.0.0
 *
 * @property \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData $processData
 */
class PostProcessingNotificationHandler extends AbstractProcessHandler
{
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

    /**
     * @return bool
     */
    protected function isFullyRefunded()
    {
        return ($this->processData->getOrderOpenAmount() - $this->processData->getTransactionRequestedAmount()) == 0;
    }
}
