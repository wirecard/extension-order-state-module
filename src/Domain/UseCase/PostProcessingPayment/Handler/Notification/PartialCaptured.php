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
        if ($this->onCaptureTransactionRequest() || $this->onRefundTransactionRequest()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PARTIAL_CAPTURED);
        }
        return $result;
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    private function onCaptureTransactionRequest()
    {
        return $this->processData->transactionInType(Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION) &&
            $this->isCaptureAmountOverRefundAmount() &&
            ($this->isNotFullCapturedAmount() || $this->isFullCapturedAndPartialRefunded());
    }

    /**
     * TransactionRequestedAmount in this case part of refund amount.
     * In this case during refund transaction capture amount should be over refund amount and
     * capture amount should be full captured.
     * Thus refund transaction should return partial captured order state in regard to order states handling schema.
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @todo : refactor this part after all possible cases in this scope covered.
     */
    private function onRefundTransactionRequest()
    {
        return $this->processData->transactionTypeInRange([
                Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
                Constant::TRANSACTION_TYPE_VOID_CAPTURE
            ]) && $this->isNotFullCapturedAmountOnRefundContext() &&
            $this->isCaptureAmountOverRefundAmountOnRefundContext();
    }

    /**
     * @return bool
     */
    private function isCaptureAmountOverRefundAmountOnRefundContext()
    {
        return $this->differenceImplicitPrecision(
            $this->processData->getOrderCapturedAmount(),
            $this->processData->getOrderRefundedAmount() + $this->processData->getTransactionRequestedAmount()
        ) > 0;
    }

    /**
     * @return float
     */
    private function getCalculatedCaptureTotalAmount()
    {
        return $this->processData->getOrderCapturedAmount() + $this->processData->getTransactionRequestedAmount();
    }

    /**
     * @return bool
     */
    private function isNotFullCapturedAmount()
    {
        return $this->differenceImplicitPrecision(
            $this->processData->getOrderTotalAmount(),
            $this->getCalculatedCaptureTotalAmount()
        ) > 0.0;
    }

    /**
     * @return bool
     */
    private function isNotFullCapturedAmountOnRefundContext()
    {
        return $this->differenceImplicitPrecision(
            $this->processData->getOrderTotalAmount(),
            $this->processData->getOrderCapturedAmount()
        ) > 0.0;
    }

    /**
     * @return bool
     */
    private function isFullCapturedAndPartialRefunded()
    {
        $isFullCapturedAmount = $this->isFloatEquals(
            $this->getCalculatedCaptureTotalAmount(),
            $this->processData->getOrderTotalAmount()
        );
        return $isFullCapturedAmount && $this->processData->getOrderRefundedAmount() > 0.0;
    }

    /**
     * @return bool
     */
    private function isCaptureAmountOverRefundAmount()
    {
        return $this->differenceImplicitPrecision(
            $this->getCalculatedCaptureTotalAmount(),
            $this->processData->getOrderRefundedAmount()
        ) > 0.0;
    }
}
