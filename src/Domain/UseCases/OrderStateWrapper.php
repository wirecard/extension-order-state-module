<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCases;

use Wirecard\ExtensionOrderStateModule\Domain\Entities\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entities\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\OrderStateDataRegistry;

class OrderStateWrapper
{
    /**
     * @var OrderStateMapper
     */
    private $mapper;
    /**
     * @var OrderState
     */
    private $targetState;

    public function __construct(OrderStateMapper $mapper, OrderState $targetState)
    {
        $this->mapper = $mapper;
        $this->targetState = $targetState;
    }

    /**
     * @param $state
     * @return OrderState
     * @throws \Exception
     */
    public function get($state)
    {
        return OrderStateDataRegistry::getInstance()->get($state);
    }

    /**
     * @param OrderState $state
     * @param string $rawState
     * @return bool
     * @throws \Exception
     */
    public function isEqual(OrderState $state, $rawState)
    {
        return $this->get($rawState)->equalsTo($state);
    }

    public function inSet(OrderState $state, array $set)
    {
        $valueObjectSet = array_map(function ($stateValue) {
            return OrderStateDataRegistry::getInstance()->get($stateValue);
        }, $set);

        return $state->inSet($valueObjectSet);
    }

    /**
     * @param OrderState $state
     * @return int|string|null
     * @throws \Exception
     */
    public function toExternal(OrderState $state)
    {
        $foundType = null;
        foreach ($this->mapper->map() as $externalType => $orderStateVO) {
            if ($state->equalsTo($orderStateVO)) {
                $foundType = $externalType;
                break;
            }
        }

        if (null === $foundType) {
            throw new \Exception("{$state} isn't defined in mapper!");
        }

        return $foundType;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isPending()
    {
        return $this->isEqual($this->targetState, Constant::ORDER_STATE_PENDING);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isStarted()
    {
        return $this->isEqual($this->targetState, Constant::ORDER_STATE_STARTED);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isFailed()
    {
        return $this->isEqual($this->targetState, Constant::ORDER_STATE_FAILED);
    }
}
