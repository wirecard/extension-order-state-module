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
 * Class Refunded
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturn
 * @since 1.0.0
 */
class Refunded extends PostProcessingNotificationHandler
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
        if ($this->processData->transactionTypeInRange(
                [
                    Constant::TRANSACTION_TYPE_VOID_PURCHASE,
                    Constant::TRANSACTION_TYPE_REFUND_PURCHASE,
                    Constant::TRANSACTION_TYPE_REFUND_DEBIT,
                    Constant::TRANSACTION_TYPE_CREDIT,
                ]
        ) && $this->isFullyRefunded()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_REFUNDED);
        }

        return $result;
    }
}
