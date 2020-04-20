<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Example;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;

/**
 * Sample implementation of Input contract for handling order states
 * Class SampleInputDTO
 * @package Wirecard\ExtensionOrderStateModule\Example
 * @since 1.0.0
 */
class SampleInputDTO implements InputDataTransferObject
{
    /**
     * @var string
     */
    private $processType;

    /**
     * @var string
     */
    private $currentOrderState;

    /**
     * @var string
     */
    private $transactionType;

    /**
     * @var string
     */
    private $transactionState;

    /**
     * @return string
     */
    public function getProcessType()
    {
        return $this->processType;
    }

    /**
     * @param string $processType
     */
    public function setProcessType($processType)
    {
        $this->processType = $processType;
    }

    /**
     * @return string
     */
    public function getCurrentOrderState()
    {
        return $this->currentOrderState;
    }

    /**
     * @param string $currentOrderState
     */
    public function setCurrentOrderState($currentOrderState)
    {
        $this->currentOrderState = $currentOrderState;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param string $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return string
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }

    /**
     * @param string $transactionState
     */
    public function setTransactionState($transactionState)
    {
        $this->transactionState = $transactionState;
    }
}
