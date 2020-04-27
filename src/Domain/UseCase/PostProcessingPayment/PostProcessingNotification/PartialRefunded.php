<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotification;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotificationHandler;

/**
 * Class PartialRefunded
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotification
 * @since 1.0.0
 */
class PartialRefunded extends PostProcessingNotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Refunded($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->transactionTypeInRange(
            [Constant::TRANSACTION_TYPE_VOID_PURCHASE, Constant::TRANSACTION_TYPE_REFUND_PURCHASE]
        ) && !$this->isFullyRefunded()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PARTIAL_REFUNDED);
        }

        return $result;
    }
}
