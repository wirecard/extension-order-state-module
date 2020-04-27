<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Service;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Factory\ProcessFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Service\ProcessHandlerService;

/**
 * Class OrderState
 *
 * This service manages next order state calculation
 *
 * Example of usage:
 *
 * $mappingDefinition = new MappingDefinition();
 * $mapper = new OrderStateMapper($mappingDefinition)
 * $service = new OrderState($mapper);
 * $nextOrderState = $service->process(InputDataTransferObject);
 *
 * MappingDefinition: definition of reference between internal and external order states.
 * @see MappingDefinition
 * OrderStateMapper:  mapper between internal and external states.
 * @see OrderStateMapper
 * InputDataTransferObject: -> data transfer object between client and module sides.
 * @see InputDataTransferObject
 *
 * @package Wirecard\ExtensionOrderStateModule\Application\Service
 * @since 1.0.0
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
     * Calculate next order state
     *
     * @param InputDataTransferObject $data
     * @return mixed|int|string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException
     * @todo: create interface for return type
     */
    public function process(InputDataTransferObject $data)
    {
        $process = (new ProcessFactory($data, $this->mapper))->create();
        $orderState = (new ProcessHandlerService($process))->handle();
        return $this->mapper->toExternal($orderState);
    }
}
