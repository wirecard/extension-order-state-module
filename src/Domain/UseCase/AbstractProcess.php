<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;

/**
 * Class AbstractProcess
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase
 * @since 1.0.0
 */
abstract class AbstractProcess
{
    /**
     * @var InputDataTransferObject
     */
    protected $input;
    /**
     * @var OrderStateMapper
     */
    protected $mapper;

    /**
     * AbstractProcess constructor.
     * @param InputDataTransferObject $input
     * @param OrderStateMapper $mapper
     */
    public function __construct(InputDataTransferObject $input, OrderStateMapper $mapper)
    {
        $this->input = $input;
        $this->mapper = $mapper;
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData
     */
    abstract protected function createProcessData();

    /**
     * @return AbstractProcessHandler
     */
    abstract public function createHandler();
}
