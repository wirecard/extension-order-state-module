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
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotificationHandler;

/**
 * Class Failed
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturn
 * @since 1.0.0
 */
class Failed extends PostProcessingNotificationHandler
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
        if ($this->processData->transactionInState(Constant::TRANSACTION_STATE_FAILED)) {
            throw new IgnorablePostProcessingFailureException();
        }
        return $result;
    }
}
