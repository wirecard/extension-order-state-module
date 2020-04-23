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
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class ProcessDataFactory
 * @package Wirecard\ExtensionOrderStateModule\Domain\Factory
 * @since 1.0.0
 */
class ProcessDataFactory
{
    use DataRegistry;

    /**
     * @var OrderStateMapper
     */
    private $mapper;

    public function __construct(OrderStateMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param InputDataTransferObject $inputData
     * @return ProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function create(InputDataTransferObject $inputData)
    {
        return new ProcessData(
            $this->mapper->toInternal($inputData->getCurrentOrderState()),
            $this->fromTransactionTypeRegistry($inputData->getTransactionType()),
            new TransactionState($inputData->getTransactionState())
        );
    }
}
