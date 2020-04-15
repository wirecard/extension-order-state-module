<?php

namespace example;

ini_set("display_errors", true);

$pwd = dirname(__FILE__);
require_once  dirname($pwd) . "/vendor/autoload.php";
require_once $pwd . DIRECTORY_SEPARATOR . 'SampleInputTransferObject.php';
require_once $pwd . DIRECTORY_SEPARATOR . 'SampleOrderStateMapper.php';

use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;

$orderStateService = new OrderState(new SampleOrderStateMapper());


try {
    // Processing
    $inputDTO = new SampleInputTransferObject();
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);
    $result = $orderStateService->process($inputDTO);

    print_r((string)$inputDTO . PHP_EOL);
    print_r("Result: {$result}" . PHP_EOL);
    print_r("-----------------------" . PHP_EOL);

    // Failed
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_FAILURE);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);

    $result = $orderStateService->process($inputDTO);
    print_r((string)$inputDTO . PHP_EOL);
    print_r("Result: {$result}" . PHP_EOL);
} catch (InvalidValueException $e) {
    print_r($e->getMessage() . PHP_EOL);
}
