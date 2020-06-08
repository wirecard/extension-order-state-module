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
 * Class Cancelled
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @since 1.0.0
 * @todo: create common class for this collection of constants.
 */
class Canceled extends NotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Processing($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->orderInState(Constant::ORDER_STATE_AUTHORIZED) &&
            $this->processData->transactionInType(Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION) &&
            $this->isFullAmountRequested() &&
            $this->isNeverRefundedOrCaptured()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_CANCELED);
        }
        return $result;
    }

    /**
     * @return bool
     */
    private function isNeverRefundedOrCaptured()
    {

        return $this->isFloatEquals($this->processData->getOrderRefundedAmount(), 0.0) &&
            $this->isFloatEquals($this->processData->getOrderCapturedAmount(), 0.0);
    }
}
