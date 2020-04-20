<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn\Failed;

/**
 * Class ReturnOrderStateManager
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment
 */
class InitialReturnHandler extends AbstractProcessHandler
{
    /**
     * @return AbstractProcessHandler|null
     * @since 1.0.0
     */
    protected function getNextHandler()
    {
        return new Failed($this->processData);
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function calculate()
    {
        return null;
    }
}
