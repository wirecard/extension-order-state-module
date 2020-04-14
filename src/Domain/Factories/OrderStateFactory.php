<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Factories;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Authorized;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Pending;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\Started;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException;

/**
 * Class OrderStateFactory
 * @package Wirecard\ExtensionOrderStateModule\Domain\Factories
 */
class OrderStateFactory
{
    /**
     * @param string $orderState
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState\OrderStateValueObject
     * @throws InvalidValueException
     */
    public function create($orderState)
    {
        switch ($orderState) {
            case Constant::ORDER_STATE_STARTED:
                return new Started();
            case Constant::ORDER_STATE_PENDING:
                return new Pending();
            case Constant::ORDER_STATE_AUTHORIZED:
                return new Authorized();
            case Constant::ORDER_STATE_FAILED:
                return new Failed();
            case Constant::ORDER_STATE_PROCESSING:
                return new Processing();
            default:
                throw new InvalidValueException("Order state {$orderState} is invalid or not implemented");
        }
    }
}
