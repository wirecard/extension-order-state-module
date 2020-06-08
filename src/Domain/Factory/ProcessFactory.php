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
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialReturn;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingNotification;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturn;

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

    /** @var int */
    private $precision;

    public function __construct(InputDataTransferObject $inputData, OrderStateMapper $mapper, $precision)
    {
        $this->mapper = $mapper;
        $this->inputData = $inputData;
        $this->precision = $precision;
    }


    /**
     * @return AbstractProcess
     * @throws InvalidProcessTypeException
     * TODO: use polymorphism
     */
    public function create()
    {
        switch ($this->inputData->getProcessType()) {
            case Constant::PROCESS_TYPE_INITIAL_RETURN:
                return new InitialReturn($this->inputData, $this->mapper);
            case Constant::PROCESS_TYPE_INITIAL_NOTIFICATION:
                return new InitialNotification($this->inputData, $this->mapper);
            case Constant::PROCESS_TYPE_POST_PROCESSING_RETURN:
                return new PostProcessingReturn($this->inputData, $this->mapper, $this->precision);
            case Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION:
                return new PostProcessingNotification($this->inputData, $this->mapper, $this->precision);
            default:
                throw new InvalidProcessTypeException("Invalid process type {$this->inputData->getProcessType()}");
        }
    }
}
