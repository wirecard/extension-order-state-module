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
 * Class Processing
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @since 1.0.0
 */
class Processing extends NotificationHandler
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
        if ($this->isOrderStateAllowed() &&
            $this->processData->transactionInType(Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION) &&
            $this->isNeverRefunded() &&
            $this->isFullAmountCaptured()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PROCESSING);
        }
        return $result;
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    private function isOrderStateAllowed()
    {
        return $this->processData->orderInState(Constant::ORDER_STATE_AUTHORIZED) ||
            $this->processData->orderInState(Constant::ORDER_STATE_PARTIAL_CAPTURED);
    }

    /**
     * @return bool
     */
    private function isNeverRefunded()
    {
        return $this->isFloatEquals($this->processData->getOrderRefundedAmount(), 0.0);
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
    private function isFullAmountCaptured()
    {
        return $this->isFloatEquals(
            $this->getCalculatedCaptureTotalAmount(),
            $this->processData->getOrderTotalAmount()
        );
    }
}
