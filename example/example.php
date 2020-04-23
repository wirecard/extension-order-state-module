<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

/**
 * Example of how to use and implement module on client side
 * @since 1.0.0
 */
namespace Wirecard\ExtensionOrderStateModule\Example;

require_once  dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;

try {
    $mappingDefinition = new SampleMappingDefinition();

    print_r("Possible map overview:" . PHP_EOL);
    print_r("***************************" . PHP_EOL);
    print_r($mappingDefinition->definitions());
    print_r("***************************" . PHP_EOL);

    $orderStateService = new OrderState(new GenericOrderStateMapper($mappingDefinition));

    // Processing
    $inputDTO = new SampleInputDTO();
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_NOTIFICATION);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState("started_external");

    $result = $orderStateService->process($inputDTO);

    print_r("Input: {$inputDTO}" . PHP_EOL);
    print_r("Result: {$result}" . PHP_EOL);
    print_r("-----------------------" . PHP_EOL);

    // Failed
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_DEBIT);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_FAILURE);
    $inputDTO->setCurrentOrderState("pending_external");

    $result = $orderStateService->process($inputDTO);

    print_r("Input: {$inputDTO}" . PHP_EOL);
    print_r("Result: {$result}" . PHP_EOL);
    print_r("-----------------------" . PHP_EOL);

    $inputDTO->setProcessType(Constant::PROCESS_TYPE_RETURN);
    $inputDTO->setTransactionType(Constant::TRANSACTION_TYPE_AUTHORIZE);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);
    $inputDTO->setCurrentOrderState(Constant::ORDER_STATE_AUTHORIZED);

    print_r("Input: {$inputDTO}" . PHP_EOL);
    $orderStateService->process($inputDTO);

} catch (IgnorableStateException $exception) {
    print_r("Result:" . $exception->getMessage() . PHP_EOL);
    print_r("Preform follow-up actions" . PHP_EOL);
} catch (OrderStateInvalidArgumentException $exception) {
    print_r("Result:" . $exception->getMessage() . PHP_EOL);
    print_r("Internal validation" . PHP_EOL);
}
