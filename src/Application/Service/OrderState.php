<?php


namespace Wirecard\ExtensionOrderStateModule\Application\Service;


use Wirecard\ExtensionOrderStateModule\Domain\Factories\OrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

class OrderState
{
    /**
     * @var OrderStateMapper
     */
    private $mapper;

    public function __construct(OrderStateMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function process(InputDataTransferObject $data) {
        //process calculates the next state
        return $this->getOrderStateManager()->process($this->mapper, $data);
    }

    private function getOrderStateManager() {
        return (new OrderStateManager())->create();
    }
}