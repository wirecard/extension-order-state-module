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
        return new PartialCaptured($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->orderInState(Constant::ORDER_STATE_AUTHORIZED) &&
            $this->processData->transactionInType(Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION) &&
            $this->isNeverRefunded() &&
            $this->isFullAmountCaptured()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PROCESSING);
        }
        return $result;
    }

    private function isNeverRefunded()
    {
        return !$this->processData->getOrderRefundedAmount();
    }

    /**
     * @return bool
     */
    private function isFullAmountCaptured()
    {
        $result = false;
        $capturedTotalAmount = $this->processData->getOrderCapturedAmount() +
            $this->processData->getTransactionRequestedAmount();
        if ($capturedTotalAmount == $this->processData->getOrderTotalAmount()) {
            $result = true;
        }

        return $result;
    }
}
