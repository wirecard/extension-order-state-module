<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Factory;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class ProcessDataFactory
 * @package Wirecard\ExtensionOrderStateModule\Domain\Factory
 */
class ProcessDataFactory
{
    use DataRegistry;

    /**
     * @param InputDataTransferObject $inputData
     * @return ProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @since 1.0.0
     */
    public function create(InputDataTransferObject $inputData)
    {
        return new ProcessData(
            $this->fromOrderStateRegistry($inputData->getCurrentOrderState()),
            $this->fromTransactionTypeRegistry($inputData->getTransactionType()),
            new TransactionState($inputData->getTransactionState())
        );
    }
}
