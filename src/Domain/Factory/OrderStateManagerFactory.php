<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Factory;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\NotificationOrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\ReturnOrderStateManager;
use InvalidArgumentException;

/**
 * Class OrderStateManagerFactory
 * @package Wirecard\ExtensionOrderStateModule\Domain\Factory
 */
class OrderStateManagerFactory
{
    /**
     * @var InputDataTransferObject
     */
    private $input;
    /**
     * @var OrderStateMapper
     */
    private $mapper;

    public function __construct(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $this->input = $input;
        $this->mapper = $mapper;
    }

    /**
     * @return NotificationOrderStateManager|ReturnOrderStateManager
     * @throws InvalidArgumentException
     */
    public function create()
    {
        switch ($this->input->getProcessType()) {
            case Constant::PROCESS_TYPE_NOTIFICATION:
                return new NotificationOrderStateManager();
            case Constant::PROCESS_TYPE_RETURN:
                return new ReturnOrderStateManager($this->mapper, $this->input);
            default:
                throw new InvalidArgumentException("Invalid process type: {$this->input->getProcessType()}");
        }
    }
}
