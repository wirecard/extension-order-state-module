<?php


namespace Wirecard\ExtensionOrderStateModule\Application\Service;

use Wirecard\ExtensionOrderStateModule\Domain\OrderStateManagerFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class OrderState
{
    /**
     * @var OrderStateMapper
     */
    private $mapper;

    /**
     * OrderState constructor.
     * @param OrderStateMapper $mapper
     */
    public function __construct(OrderStateMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param InputDataTransferObject $data
     * @return mixed
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException
     */
    public function process(InputDataTransferObject $data)
    {
        //process calculates the next state
        $factory = (new OrderStateManagerFactory($data))->create();
        return $factory->process($data, $this->mapper);
    }
}
