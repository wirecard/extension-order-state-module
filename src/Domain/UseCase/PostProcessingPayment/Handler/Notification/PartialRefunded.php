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
 * @todo: create common class for this collection of constants.
 */
class PartialRefunded extends NotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new PartialCaptured($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->isAllowedTransactionType() && $this->isNotFullRefundedAmount() &&
            ($this->isRefundAmountOverCaptureAmount() || $this->isFullAmountCaptured())) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PARTIAL_REFUNDED);
        }

        return $result;
    }

    /**
     * @return float
     */
    private function getCalculatedRefundTotalAmount()
    {
        return $this->processData->getOrderRefundedAmount() + $this->processData->getTransactionRequestedAmount();
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    private function isAllowedTransactionType()
    {
        return $this->processData->transactionTypeInRange([
            Constant::TRANSACTION_TYPE_VOID_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
            Constant::TRANSACTION_TYPE_REFUND_DEBIT,
            Constant::TRANSACTION_TYPE_CREDIT,
            Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
            Constant::TRANSACTION_TYPE_VOID_CAPTURE
        ]);
    }

    /**
     * @return bool
     */
    private function isNotFullRefundedAmount()
    {
        return $this->differenceImplicitPrecision(
            $this->processData->getOrderTotalAmount(),
            $this->getCalculatedRefundTotalAmount()
        ) > 0.0;
    }

    /**
     * @return bool
     */
    private function isRefundAmountOverCaptureAmount()
    {
        return $this->differenceImplicitPrecision(
            $this->getCalculatedRefundTotalAmount(),
            $this->processData->getOrderCapturedAmount()
        ) >= 0.0;
    }

    /**
     * @return bool
     */
    private function isFullAmountCaptured()
    {
        return $this->isFloatEquals(
            $this->processData->getOrderTotalAmount(),
            $this->processData->getOrderCapturedAmount()
        );
    }
}
