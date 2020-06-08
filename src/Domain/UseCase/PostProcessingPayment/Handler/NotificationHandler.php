<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler;

use Wirecard\ExtensionOrderStateModule\Domain\Helper\Numeric;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\Notification\Failed;

/**
 * Class PostProcessingNotificationHandler
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment
 * @since 1.0.0
 *
 * @property \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData $processData
 */
class NotificationHandler extends AbstractProcessHandler
{
    use Numeric;

    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Failed($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        return null;
    }

    /**
     * @param float $firstNumber
     * @param float $secondNumber
     * @return bool
     */
    protected function isFloatEquals($firstNumber, $secondNumber)
    {
        return $this->equals($firstNumber, $secondNumber, $this->processData->getPrecision());
    }

    /**
     * @param float $firstNumber
     * @param float $secondNumber
     * @return float|int
     */
    protected function differenceImplicitPrecision($firstNumber, $secondNumber)
    {
        return $this->difference($firstNumber, $secondNumber, $this->processData->getPrecision());
    }

    /**
     * @return bool
     */
    protected function isFullAmountRequested()
    {
        return $this->isFloatEquals(
            $this->processData->getTransactionRequestedAmount(),
            $this->processData->getOrderTotalAmount()
        );
    }
}
