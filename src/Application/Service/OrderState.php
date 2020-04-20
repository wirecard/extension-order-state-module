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
use Wirecard\ExtensionOrderStateModule\Domain\Factory\ProcessDataFactory;
use Wirecard\ExtensionOrderStateModule\Domain\Service\ProcessHandlerService;

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
     * @return mixed|int|string
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException
     * @todo: create interface for return type
     */
    public function process(InputDataTransferObject $data)
    {
        $processData = (new ProcessDataFactory())->create($data);
        $orderState = (new ProcessHandlerService($data->getProcessType(), $processData))->handle();
        return $this->mapper->toExternal($orderState);
    }
}
