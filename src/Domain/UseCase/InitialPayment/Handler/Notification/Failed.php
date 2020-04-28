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
 * Class Failed
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification
 * @since 1.0.0
 */
class Failed extends NotificationHandler
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

        if ($this->processData->orderInState(Constant::ORDER_STATE_FAILED) ||
            $this->processData->transactionInState(Constant::TRANSACTION_STATE_FAILED)) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_FAILED);
        }

        return $result;
    }
}
