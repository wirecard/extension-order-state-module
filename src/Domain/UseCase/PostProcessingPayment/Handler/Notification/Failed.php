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
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\NotificationHandler;

/**
 * Class Failed
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification
 * @since 1.0.0
 */
class Failed extends NotificationHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Canceled($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->transactionInState(Constant::TRANSACTION_STATE_FAILED)) {
            throw new IgnorablePostProcessingFailureException();
        }
        return $result;
    }
}
