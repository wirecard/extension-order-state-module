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
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotification
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
        return new Refunded($this->processData);
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
            ]
        ) && !$this->isFullyRefunded()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PARTIAL_REFUNDED);
        }

        return $result;
    }
}
