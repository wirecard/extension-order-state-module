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
 * Class Refunded
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @since 1.0.0
 */
class Refunded extends NotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new PartialRefunded($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->transactionTypeInRange(
            [
                    Constant::TRANSACTION_TYPE_VOID_PURCHASE,
                    Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
                    Constant::TRANSACTION_TYPE_REFUND_DEBIT,
                    Constant::TRANSACTION_TYPE_CREDIT,
                    Constant::TRANSACTION_TYPE_REFUND_CAPTURE,
                    Constant::TRANSACTION_TYPE_VOID_CAPTURE,
                ]
        ) && $this->isFullAmountRefunded()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_REFUNDED);
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
     */
    private function isFullAmountRefunded()
    {
        return $this->isFloatEquals(
            $this->getCalculatedRefundTotalAmount(),
            $this->processData->getOrderTotalAmount()
        );
    }
}
