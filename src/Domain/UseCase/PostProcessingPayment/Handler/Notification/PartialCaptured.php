<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;

/**
 * Class PartialRefunded
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @since 1.0.0
 */
class PartialCaptured extends NotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->transactionInType(Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION) &&
            $this->isNotFullCaptureAmount() &&
            $this->isCaptureAmountOverRefundAmount()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PARTIAL_CAPTURED);
        }
        return $result;
    }

    /**
     * @return bool
     */
    private function isNotFullCaptureAmount()
    {
        $result = false;
        $capturedTotalAmount = $this->processData->getOrderCapturedAmount() +
            $this->processData->getTransactionRequestedAmount();
        if ($capturedTotalAmount < $this->processData->getOrderTotalAmount()) {
            $result = true;
        }
        return $result;
    }

    /**
     * @return bool
     */
    private function isCaptureAmountOverRefundAmount()
    {
        return $this->processData->getOrderCapturedAmount() > $this->processData->getOrderRefundedAmount();
    }
}
