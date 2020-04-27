<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Entity;

/**
 * Class Constant
 * @package Wirecard\ExtensionOrderStateModule\Domain\Entity
 * @since 1.0.0
 */
class Constant
{
    // Process Type
    const PROCESS_TYPE_INITIAL_RETURN = "initial-return";
    const PROCESS_TYPE_INITIAL_NOTIFICATION = "initial-notification";
    const PROCESS_TYPE_POST_PROCESSING_RETURN = "post-processing-return";
    const PROCESS_TYPE_POST_PROCESSING_NOTIFICATION = "post-processing-notification";
    // Order State
    const ORDER_STATE_STARTED = "started";
    const ORDER_STATE_PENDING = "pending";
    const ORDER_STATE_FAILED = "failed";
    const ORDER_STATE_AUTHORIZED = "authorized";
    const ORDER_STATE_PROCESSING = "processing";
    const ORDER_STATE_REFUNDED = "refunded";
    const ORDER_STATE_PARTIAL_REFUNDED = "refunded";
    // Transaction State
    const TRANSACTION_STATE_FAILED = "failed";
    const TRANSACTION_STATE_SUCCESS = "success";
    // Transaction Type
    const TRANSACTION_TYPE_AUTHORIZE = "authorization";
    const TRANSACTION_TYPE_DEBIT = "debit";
    const TRANSACTION_TYPE_PENDING_DEBIT = "pending-debit";
    const TRANSACTION_TYPE_PURCHASE = "purchase";
    const TRANSACTION_TYPE_DEPOSIT = "deposit";
    const TRANSACTION_TYPE_CHECK_PAYER_RESPONSE = "check-payer-response";
    const TRANSACTION_TYPE_VOID_PURCHASE = "void-purchase";
    const TRANSACTION_TYPE_REFUND_PURCHASE = "refund-purchase";

    /**
     * @return array
     * @since 1.0.0
     */
    public static function getOrderStates()
    {
        return [
            self::ORDER_STATE_STARTED,
            self::ORDER_STATE_PENDING,
            self::ORDER_STATE_FAILED,
            self::ORDER_STATE_AUTHORIZED,
            self::ORDER_STATE_PROCESSING,
            self::ORDER_STATE_REFUNDED,
            self::ORDER_STATE_PARTIAL_REFUNDED,
        ];
    }

    /**
     * @return array
     */
    public static function getTransactionTypes()
    {
        return [
            self::TRANSACTION_TYPE_AUTHORIZE,
            self::TRANSACTION_TYPE_DEBIT,
            self::TRANSACTION_TYPE_PENDING_DEBIT,
            self::TRANSACTION_TYPE_PURCHASE,
            self::TRANSACTION_TYPE_DEPOSIT,
            self::TRANSACTION_TYPE_CHECK_PAYER_RESPONSE,
            self::TRANSACTION_TYPE_VOID_PURCHASE,
            self::TRANSACTION_TYPE_REFUND_PURCHASE,
        ];
    }

    /**
     * @return array
     */
    public static function getTransactionStates()
    {
        return [
            self::TRANSACTION_STATE_SUCCESS,
            self::TRANSACTION_STATE_FAILED,
        ];
    }

    /**
     * @return array
     */
    public static function getProcessTypes()
    {
        return [
            self::PROCESS_TYPE_INITIAL_RETURN,
            self::PROCESS_TYPE_INITIAL_NOTIFICATION,
            self::PROCESS_TYPE_POST_PROCESSING_RETURN,
            self::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
        ];
    }
}
