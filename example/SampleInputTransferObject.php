<?php


namespace example;

use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;

class SampleInputTransferObject implements InputDataTransferObject
{
    private $processType;
    private $transactionType;
    private $transactionState;
    private $currentOrderState;

    /**
     * @return mixed
     */
    public function getProcessType()
    {
        return $this->processType;
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return mixed
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }

    /**
     * @return mixed
     */
    public function getCurrentOrderState()
    {
        return $this->currentOrderState;
    }

    /**
     * @param mixed $processType
     */
    public function setProcessType($processType)
    {
        $this->processType = $processType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @param mixed $transactionState
     */
    public function setTransactionState($transactionState)
    {
        $this->transactionState = $transactionState;
    }

    /**
     * @param mixed $currentOrderState
     */
    public function setCurrentOrderState($currentOrderState)
    {
        $this->currentOrderState = $currentOrderState;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = "";
        $str .= "PT: {$this->getProcessType()} |";
        $str .= "TT: {$this->getTransactionType()} |";
        $str .= "TS: {$this->getTransactionState()} |";
        $str .= "CO: {$this->getCurrentOrderState()}";
        return $str;
    }
}
