<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Service;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidProcessTypeException;
use Wirecard\ExtensionOrderStateModule\Domain\Factory\ProcessDataFactory;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotificationHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturnHandler;

/**
 * Class ProcessHandlerService
 * @package Wirecard\ExtensionOrderStateModule\Domain\Service
 */
class ProcessHandlerService
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
     * ProcessHandlerService constructor.
     * @param InputDataTransferObject $data
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @since 1.0.0
     */
    public function __construct(InputDataTransferObject $data)
    {
        $this->processType = $data->getProcessType();
        $this->processData = (new ProcessDataFactory())->create($data);
    }

    /**
     * @throws InvalidProcessTypeException
     */
    private function findHandler()
    {
        switch ($this->processType) {
            case Constant::PROCESS_TYPE_RETURN:
                return $this->findReturnHandler();
            case Constant::PROCESS_TYPE_NOTIFICATION:
                return $this->findNotificationHandler();
            default:
                throw new InvalidProcessTypeException("Invalid process type {$this->processType}");
        }
    }

    /**
     * @return InitialReturnHandler
     *@todo encapsulate return finder e.g InitialReturn | PostProcessingReturn
     */
    private function findReturnHandler()
    {
        return new InitialReturnHandler($this->processData);
    }

    /**
     * @todo encapsulate return finder e.g PostProcessingNotification | PostProcessingNotification
     * @return null
     */
    private function findNotificationHandler()
    {
        return new InitialNotificationHandler($this->processData);
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     * @throws InvalidProcessTypeException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function handle()
    {
        return $this->findHandler()->handle();
    }
}
