<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\Notification;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\NotificationHandler;

/**
 * Class Processing
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification
 * @since 1.0.0
 */
class Processing extends NotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Authorization($this->processData);
    }

    /**
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    private function isAllowedTransactionType()
    {
        return $this->processData->transactionTypeInRange([
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_TYPE_DEPOSIT,
        ]);
    }


    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->isSuccessTransaction() && $this->isAllowedTransactionType()) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_PROCESSING);
        }

        return $result;
    }
}
