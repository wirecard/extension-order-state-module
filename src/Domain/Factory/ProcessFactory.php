<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Factory;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidProcessTypeException;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\Process\InitialNotification;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\Process\InitialReturn;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\Process\PostProcessingNotification;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\Process\PostProcessingReturn;

/**
 * Class ProcessDataFactory
 * @package Wirecard\ExtensionOrderStateModule\Domain\Factory
 * @since 1.0.0
 */
class ProcessFactory
{
    /**
     * @var OrderStateMapper
     */
    private $mapper;
    /**
     * @var InputDataTransferObject
     */
    private $inputData;

    public function __construct(InputDataTransferObject $inputData, OrderStateMapper $mapper)
    {
        $this->mapper = $mapper;
        $this->inputData = $inputData;
    }


    /**
     * @return AbstractProcess
     * @throws InvalidProcessTypeException
     */
    public function create()
    {
        switch ($this->inputData->getProcessType()) {
            case Constant::PROCESS_TYPE_INITIAL_RETURN:
                return new InitialReturn($this->inputData, $this->mapper);
            case Constant::PROCESS_TYPE_INITIAL_NOTIFICATION:
                return new InitialNotification($this->inputData, $this->mapper);
            case Constant::PROCESS_TYPE_POST_PROCESSING_RETURN:
                return new PostProcessingReturn($this->inputData, $this->mapper);
            case Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION:
                return new PostProcessingNotification($this->inputData, $this->mapper);
            default:
                throw new InvalidProcessTypeException("Invalid process type {$this->inputData->getProcessType()}");
        }
    }
}
