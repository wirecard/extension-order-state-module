<?php

namespace example;

ini_set("display_errors", true);

$pwd = dirname(__FILE__);
require_once  dirname($pwd) . "/vendor/autoload.php";
require_once $pwd . DIRECTORY_SEPARATOR . 'SampleMapDefinition.php';

use Wirecard\ExtensionOrderStateModule\Application\Input\GenericInputDTO;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;

$orderStateService = new OrderState(new GenericOrderStateMapper(new SampleMapDefinition()));

try {
    // Processing
    $inputDTO = new GenericInputDTO();
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);

    $result = $orderStateService->process($inputDTO);

    print_r("Result: {$result}" . PHP_EOL);
    print_r("-----------------------" . PHP_EOL);

    // Failed
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_FAILURE);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_STARTED);

    $result = $orderStateService->process($inputDTO);
    print_r("Result: {$result}" . PHP_EOL);
} catch (\Exception $e) {
    print_r($e->getMessage() . PHP_EOL);
}
