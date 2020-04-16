<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Service;

use Wirecard\ExtensionOrderStateModule\Domain\Factories\OrderStateManagerFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

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

    public function process(InputDataTransferObject $data)
    {
        //process calculates the next state
        $manager = (new OrderStateManagerFactory($data, $this->mapper))->create();
        return $manager->process($data, $this->mapper);
    }
}
