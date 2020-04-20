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
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\NotificationOrderStateManager;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\ReturnOrderStateManager;
use InvalidArgumentException;

/**
 * Class OrderStateManagerFactory
 * @package Wirecard\ExtensionOrderStateModule\Domain\Factory
 */
class ProcessHandlerFactory
{
    /**
     * @var string
     */
    private $processType;

    /**
     * @var ProcessData
     */
    private $processData;

    /**
     * OrderStateManagerFactory constructor.
     * @param InputDataTransferObject $input
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function __construct(InputDataTransferObject $input)
    {
        $this->processType = $input->getProcessType();
        $this->processData = (new ProcessDataFactory())->create($input);
    }

    /**
     * @return NotificationOrderStateManager|ReturnOrderStateManager
     * @throws InvalidArgumentException
     */
    public function create()
    {
        switch ($this->processType) {
            case Constant::PROCESS_TYPE_NOTIFICATION:
                return new NotificationOrderStateManager();
            case Constant::PROCESS_TYPE_RETURN:
                return new ReturnOrderStateManager($this->processData);
            default:
                throw new InvalidArgumentException("Invalid process type: {$this->processType}");
        }
    }
}
