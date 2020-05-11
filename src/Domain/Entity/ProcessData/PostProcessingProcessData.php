<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException;

/**
 * Class PostProcessingProcessData
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData
 * @since 1.0.0
 */
class PostProcessingProcessData extends InitialProcessData
{
    /**
     * @var float
     */
    private $transactionRequestedAmount;

    /**
     * @var float
     */
    private $orderOpenAmount;

    /**
     * PostProcessingProcessData constructor.
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @throws InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function __construct(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        parent::__construct($input, $mapper);
        $this->loadFromInput($input);
    }

    /**
     * @return float
     */
    public function getOrderOpenAmount()
    {
        return $this->orderOpenAmount;
    }

    /**
     * @return float
     */
    public function getTransactionRequestedAmount()
    {
        return $this->transactionRequestedAmount;
    }

    /**
     * @param InputDataTransferObject $input
     * @throws InvalidPostProcessDataException
     */
    protected function loadFromInput(InputDataTransferObject $input)
    {
        if (!$this->isValidFloatProperty($input->getTransactionRequestedAmount()) ||
            !$this->isValidFloatProperty($input->getOrderOpenAmount())) {
            throw new InvalidPostProcessDataException(
                "Property transactionRequestedAmount or orderOpenAmount is invalid or not provided!"
            );
        }

        if ($input->getTransactionRequestedAmount() > $input->getOrderOpenAmount()) {
            throw new InvalidPostProcessDataException(
                "Transaction requested amount (" . $input->getTransactionRequestedAmount() . ")
                can't be greater as order open amount (" . $input->getOrderOpenAmount() . ")"
            );
        }
        $this->transactionRequestedAmount = (float)$input->getTransactionRequestedAmount();
        $this->orderOpenAmount = (float)$input->getOrderOpenAmount();
    }

    /**
     * @param float $number
     * @return bool
     */
    private function isValidFloatProperty($number)
    {
        $result = false;
        if (!is_bool($number) && !is_string($number) && (float)($number) && $number > 0) {
            $result = true;
        }

        return $result;
    }
}
