<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\ReturnHandler;

/**
 * Class PostProcessingReturn
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\Process
 * @since 1.0.0
 */
class PostProcessingReturn extends AbstractProcess
{
    /**
     * @var int
     */
    private $precision;

    /**
     * PostProcessingReturn constructor.
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     * @param int $precision
     */
    public function __construct(InputDataTransferObject $input, OrderStateMapper $mapper, $precision)
    {
        parent::__construct($input, $mapper);
        $this->precision = $precision;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Constant::PROCESS_TYPE_POST_PROCESSING_RETURN;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData|InitialProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     */
    protected function createProcessData()
    {
        return new PostProcessingProcessData($this->input, $this->mapper, $this->precision);
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     */
    public function createHandler()
    {
        return new ReturnHandler($this->createProcessData());
    }
}
