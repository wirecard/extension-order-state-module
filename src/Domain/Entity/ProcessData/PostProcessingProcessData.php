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
    private $orderTotalAmount;

    /**
     * @var float
     */
    private $orderCapturedAmount;

    /**
     * @var float
     */
    private $orderRefundedAmount;

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
    public function getOrderTotalAmount()
    {
        return $this->orderTotalAmount;
    }

    /**
     * @return float
     */
    public function getTransactionRequestedAmount()
    {
        return $this->transactionRequestedAmount;
    }

    /**
     * @return float
     */
    public function getOrderCapturedAmount()
    {
        return $this->orderCapturedAmount;
    }


    /**
     * @return mixed
     */
    public function getOrderRefundedAmount()
    {
        return $this->orderRefundedAmount;
    }


    /**
     * @param InputDataTransferObject $input
     * @throws InvalidPostProcessDataException
     * @todo: FloatValueObject instead of raw float values
     */
    protected function loadFromInput(InputDataTransferObject $input)
    {
        if (!$this->isValidFloatProperty($input->getTransactionRequestedAmount())) {
            throw new InvalidPostProcessDataException(
                "Property transactionRequestedAmount is invalid or not provided!"
            );
        }

        if (!$this->isValidFloatProperty($input->getOrderTotalAmount())) {
            throw new InvalidPostProcessDataException(
                "Property orderTotalAmount is invalid or not provided!"
            );
        }

        if (!$this->isValidFloatProperty($input->getOrderCapturedAmount(), true)) {
            throw new InvalidPostProcessDataException(
                "Property orderCapturedAmount is invalid or not provided!"
            );
        }

        if (!$this->isValidFloatProperty($input->getOrderRefundedAmount(), true)) {
            throw new InvalidPostProcessDataException(
                "Property orderRefundedAmount is invalid or not provided!"
            );
        }

        if ($input->getTransactionRequestedAmount() > $input->getOrderTotalAmount()) {
            throw new InvalidPostProcessDataException(
                "Transaction requested amount (" . $input->getTransactionRequestedAmount() . ")
                can't be greater as order open amount (" . $input->getOrderTotalAmount() . ")"
            );
        }

        $this->transactionRequestedAmount = (float) $input->getTransactionRequestedAmount();
        $this->orderTotalAmount = (float) $input->getOrderTotalAmount();
        $this->orderCapturedAmount = (float) $input->getOrderCapturedAmount();
        $this->orderRefundedAmount = (float) $input->getOrderRefundedAmount();
    }

    /**
     * @param float $number
     * @param bool $allowEmpty
     * @return bool
     */
    private function isValidFloatProperty($number, $allowEmpty = false)
    {
        $result = false;
        if (!is_bool($number) && !is_string($number)) {
            $result = true;
        }

        if ($result && !$allowEmpty) {
            $result = (float) $number > 0;
        }

        return $result;
    }
}
