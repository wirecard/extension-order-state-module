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
    private $transactionRequestedAmount = 0.0;

    /**
     * @var float
     */
    private $orderTotalAmount = 0.0;

    /**
     * @var float
     */
    private $orderCapturedAmount = 0.0;

    /**
     * @var float
     */
    private $orderRefundedAmount = 0.0;

    /**
     * @var int
     */
    private $precision;

    /**
     * PostProcessingProcessData constructor.
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @param int $precision
     * @throws InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function __construct(InputDataTransferObject $input, OrderStateMapper $mapper, $precision)
    {
        parent::__construct($input, $mapper);
        $this->precision = $precision;
        $this->loadFromInput($input);
        $this->validate();
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
     * @return float
     */
    public function getOrderRefundedAmount()
    {
        return $this->orderRefundedAmount;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }


    /**
     * @param InputDataTransferObject $input
     * @todo: FloatValueObject instead of raw float values
     */
    protected function loadFromInput(InputDataTransferObject $input)
    {
        $this->transactionRequestedAmount = (float)$input->getTransactionRequestedAmount();
        $this->orderTotalAmount = (float)$input->getOrderTotalAmount();
        $this->orderCapturedAmount = (float)$input->getOrderCapturedAmount();
        $this->orderRefundedAmount = (float)$input->getOrderRefundedAmount();
    }

    /**
     * @throws InvalidPostProcessDataException
     */
    protected function validate()
    {
        $this->validateOrderTotalAmount();
        $this->validateTransactionRequestedAmount();
        $this->validateOrderCapturedAmount();
        $this->validateOrderRefundedAmount();

        if ($this->transactionRequestedAmount > $this->orderTotalAmount) {
            throw new InvalidPostProcessDataException(
                "Transaction requested amount (" . $this->transactionRequestedAmount . ")
                can't be greater as order total amount (" . $this->orderTotalAmount . ")"
            );
        }

        if ($this->orderCapturedAmount && $this->orderRefundedAmount &&
            $this->orderRefundedAmount > $this->orderCapturedAmount) {
            throw new InvalidPostProcessDataException(
                "Order Refunded amount (" . $this->orderRefundedAmount . ")
                can't be greater as Order Captured amount (" . $this->orderCapturedAmount . ")"
            );
        }
    }

    /**
     * @throws InvalidPostProcessDataException
     */
    private function validateOrderTotalAmount()
    {
        if (!$this->isValidFloatProperty($this->getOrderTotalAmount()) || $this->getOrderTotalAmount() <= 0) {
            throw new InvalidPostProcessDataException(
                "Property orderTotalAmount is invalid or not provided!"
            );
        }
    }

    /**
     * @throws InvalidPostProcessDataException
     */
    private function validateTransactionRequestedAmount()
    {
        if (!$this->isValidFloatProperty($this->getTransactionRequestedAmount()) ||
            $this->getTransactionRequestedAmount() <= 0) {
            throw new InvalidPostProcessDataException(
                "Property transactionRequestedAmount is invalid or not provided!"
            );
        }
    }

    /**
     * @throws InvalidPostProcessDataException
     */
    private function validateOrderCapturedAmount()
    {
        if (!$this->isValidFloatProperty($this->getOrderCapturedAmount())) {
            throw new InvalidPostProcessDataException(
                "Property orderCapturedAmount is invalid or not provided!"
            );
        }
    }

    /**
     * @throws InvalidPostProcessDataException
     */
    private function validateOrderRefundedAmount()
    {
        if (!$this->isValidFloatProperty($this->getOrderRefundedAmount())) {
            throw new InvalidPostProcessDataException(
                "Property orderRefundedAmount is invalid or not provided!"
            );
        }
    }

    /**
     * @param float $number
     * @return bool
     */
    private function isValidFloatProperty($number)
    {
        return filter_var($number, FILTER_VALIDATE_FLOAT) !== false;
    }
}
