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

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;

try {
    $mappingDefinition = new SampleMappingDefinition();

    print_r("Possible map definition:" . PHP_EOL);
    print_r("***************************" . PHP_EOL);
    print_r($mappingDefinition->definitions());
    print_r( PHP_EOL);
    print_r("************* Post Processing Bulk Examples **************" . PHP_EOL);
    print_r( PHP_EOL);
    $mapper = new GenericOrderStateMapper($mappingDefinition);
    $orderStateService = new OrderState($mapper);
    $inputDTO = new SampleInputDTO();
    $inputDTO->setProcessType(Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION);
    $inputDTO->setTransactionState(Constant::TRANSACTION_STATE_SUCCESS);

    $scenarios = [];
    //transactionType|orderState|orderTotalAmount|transactionRequestedAmount|orderCapturedAmount|orderRefundedAmount
    $scenarios["Cancelled authorization"] = [
        Constant::TRANSACTION_TYPE_VOID_AUTHORIZATION, Constant::ORDER_STATE_AUTHORIZED, 100, 100, 0, 0
    ];
    $scenarios["Fully captured authorization"] = [
        Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION, Constant::ORDER_STATE_AUTHORIZED, 100, 100, 0, 0
    ];
    $scenarios["Fully refunded authorization"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_AUTHORIZED, 100, 100, 0, 0
    ];
    $scenarios["Partial refunded after fully authorization capture"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_AUTHORIZED, 100, 30, 100, 0
    ];
    $scenarios["Partial refunded after not fully authorization capture"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_AUTHORIZED, 100, 70, 70, 0
    ];
    $scenarios["Partial capture after partial refund"] = [
        Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION, Constant::ORDER_STATE_AUTHORIZED, 100, 30, 0, 20
    ];
    $scenarios["Partial capture after authorization"] = [
        Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION, Constant::ORDER_STATE_AUTHORIZED, 100, 30, 0, 0
    ];
    $scenarios["Partial refund after partial capture"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_PARTIAL_CAPTURED, 100, 20, 30, 0
    ];
    $scenarios["Partial refund 1 after partial capture. Checkpoint partial refund"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_PARTIAL_CAPTURED, 100, 10, 30, 20
    ];
    $scenarios["Partial capture with amount 70 results partial capture because it was already refunded"] = [
        Constant::TRANSACTION_TYPE_CAPTURE_AUTHORIZATION, Constant::ORDER_STATE_PARTIAL_CAPTURED, 100, 70, 30, 30
    ];
    $scenarios["Next partial refund with amount 10"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_PARTIAL_CAPTURED, 100, 10, 100, 30
    ];
    $scenarios["Next partial refund with amount 60 results refunded"] = [
        Constant::TRANSACTION_TYPE_REFUND_CAPTURE, Constant::ORDER_STATE_PARTIAL_CAPTURED, 100, 60, 100, 40
    ];

    foreach ($scenarios as $scenario => $input) {
        list(
            $transactionType,
            $orderState,
            $orderTotalAmount,
            $transactionRequestedAmount,
            $orderCapturedAmount,
            $orderRefundedAmount
            ) = $input;

        $inputDTO->setTransactionType($transactionType);
        $inputDTO->setCurrentOrderState(
            $mapper->toExternal(new \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState($orderState))
        );
        $inputDTO->setOrderTotalAmount($orderTotalAmount);
        $inputDTO->setTransactionRequestedAmount($transactionRequestedAmount);
        $inputDTO->setOrderCapturedAmount($orderCapturedAmount);
        $inputDTO->setOrderRefundedAmount($orderRefundedAmount);

        $result = $orderStateService->process($inputDTO);
        print_r("------------ {$scenario} -----------" . PHP_EOL);
        print_r($inputDTO->toArray());
        print_r("Result: {$result}" . PHP_EOL);
        print_r(PHP_EOL);
    }
} catch (IgnorableStateException $exception) {
    print_r("Result:" . $exception->getMessage() . PHP_EOL);
    print_r("Preform follow-up actions" . PHP_EOL);
} catch (OrderStateInvalidArgumentException $exception) {
    print_r("Result:" . $exception->getMessage() . PHP_EOL);
    print_r("Internal validation" . PHP_EOL);
} catch (IgnorablePostProcessingFailureException $exception) {
    print_r("Result:" . $exception->getMessage() . PHP_EOL);
    print_r("Post processing failure. Something went wrong ..." . PHP_EOL);
}
