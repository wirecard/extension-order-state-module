<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturnHandler;

/**
 * Class Failed
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn
 * @since 1.0.0
 */
class Failed extends InitialReturnHandler
{
    /**
     * @inheritDoc
     */
    protected function getNextHandler()
    {
        return new Pending($this->processData);
    }

    /**
     * @inheritDoc
     */
    protected function calculate()
    {
        $result = parent::calculate();
        if ($this->processData->transactionInState(Constant::TRANSACTION_STATE_FAILED)
        ) {
            $result = $this->fromOrderStateRegistry(Constant::ORDER_STATE_FAILED);
        }

        return $result;
    }
}
