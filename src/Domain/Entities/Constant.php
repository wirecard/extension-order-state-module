<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Entities;

class Constant
{
    // Process Type
    const PROCESS_TYPE_RETURN = "return";
    const PROCESS_TYPE_NOTIFICATION = "notification";
    // Order State
    const ORDER_STATE_STARTED = "started";
    const ORDER_STATE_PENDING = "pending";
    const ORDER_STATE_FAILED = "failed";
    const ORDER_STATE_AUTHORIZED = "authorized";
    const ORDER_STATE_PROCESSING = "processing";
    // Transaction State
    const TRANSACTION_STATE_FAILURE = "failure";
    const TRANSACTION_STATE_SUCCESS = "success";
    // Transaction Type
    const TRANSACTION_TYPE_AUTHORIZE = "authorization";
    const TRANSACTION_TYPE_DEBIT = "debit";
    const TRANSACTION_TYPE_PURCHASE = "purchase";
}
