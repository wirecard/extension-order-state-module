<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Service;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidProcessTypeException;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotificationHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturnHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotificationHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturnHandler;

/**
 * Class ProcessHandlerService
 * @package Wirecard\ExtensionOrderStateModule\Domain\Service
 * @since 1.0.0
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
     * @param string $processType
     * @param ProcessData $processData
     */
    public function __construct($processType, ProcessData $processData)
    {
        $this->processType = $processType;
        $this->processData = $processData;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     * @throws IgnorableStateException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException
     */
    public function handle()
    {
        $orderState = $this->findHandler()->handle();
        if (null === $orderState) {
            throw new IgnorableStateException("State is ignored!");
        }
        return $orderState;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler
     * @throws InvalidProcessTypeException
     * @todo avoid switch | Strategy \ use polymorphism on the next stage
     */
    private function findHandler()
    {
        switch ($this->processType) {
            case Constant::PROCESS_TYPE_INITIAL_RETURN:
                return new InitialReturnHandler($this->processData);
            case Constant::PROCESS_TYPE_INITIAL_NOTIFICATION:
                return new InitialNotificationHandler($this->processData);
            case Constant::PROCESS_TYPE_POST_PROCESSING_RETURN:
                return new PostProcessingReturnHandler($this->processData);
            case Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION:
                return new PostProcessingNotificationHandler($this->processData);
            default:
                throw new InvalidProcessTypeException("Invalid process type {$this->processType}");
        }
    }
}
