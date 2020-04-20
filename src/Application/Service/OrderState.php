<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Service;

use Wirecard\ExtensionOrderStateModule\Domain\Factory\OrderStateManagerFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;

/**
 * Class OrderState
 * @package Wirecard\ExtensionOrderStateModule\Application\Service
 */
class OrderState
{
    /**
     * @var OrderStateMapper
     */
    private $mapper;

    /**
     * OrderState constructor.
     * @param OrderStateMapper $mapper
     * @since 1.0.0
     */
    public function __construct(OrderStateMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param InputDataTransferObject $data
     * @return mixed
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function process(InputDataTransferObject $data)
    {
        //process calculates the next state
        $manager = (new OrderStateManagerFactory($data, $this->mapper))->create();
        $orderState = $manager->process($data, $this->mapper);
        return $this->mapper->toExternal($orderState);
    }
}
