<?php

namespace implementation;

ini_set("display_errors", true);

$basePath = dirname(dirname(__FILE__));
require_once $basePath . "/vendor/autoload.php";


use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Authorized;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Pending;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Started;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class DefaultMapper implements OrderStateMapper
{

    public function map()
    {
        return [
            "started_external" => new Started(),
            "pending_external" => new Pending(),
            "failed_external" => new Failed(),
            "authorized_external" => new Authorized(),
            "processing_external" => new Processing(),
        ];
    }
}

$orderStateService = new OrderState(new DefaultMapper());

class PrestashopDTO implements InputDataTransferObject
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


}

try {
    // Processing
    $inputDTO = new PrestashopDTO();
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);
    $result = $orderStateService->process($inputDTO);

    print_r($result . PHP_EOL);

    // Failed
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_FAILURE);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);

    $result = $orderStateService->process($inputDTO);
    print_r($result . PHP_EOL);

    // Pending
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_PURCHASE);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);

    $result = $orderStateService->process($inputDTO);
    print_r($result . PHP_EOL);

    // Pending
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_PURCHASE);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_PENDING);

    $result = $orderStateService->process($inputDTO);
    print_r($result . PHP_EOL);

    // Pending
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_PENDING);

    $result = $orderStateService->process($inputDTO);
    print_r($result . PHP_EOL);

    // Failed
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_FAILED);

    $result = $orderStateService->process($inputDTO);
    print_r($result . PHP_EOL);

} catch (InvalidValueException $e) {
    print_r($e->getMessage() . PHP_EOL);
}
