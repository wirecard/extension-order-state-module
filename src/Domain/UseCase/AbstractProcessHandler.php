<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;

/**
 * Class AbstractProcessHandler
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase
 */
abstract class AbstractProcessHandler
{
    /**
     * @var ProcessData
     */
    protected $processData;

    /**
     * AbstractProcessHandler constructor.
     * @param ProcessData $processData
     * @since 1.0.0
     */
    public function __construct(ProcessData $processData)
    {
        $this->processData = $processData;
    }

    /**
     * @return null|\Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     * @since 1.0.0
     */
    public function handle()
    {
        $orderState = $this->calculate();
        if (null === $orderState && null !== $this->getNextHandler()) {
            $orderState = $this->getNextHandler()->handle();
        }

        return $orderState;
    }

    /**
     * @return OrderState|null
     * @since 1.0.0
     */
    abstract protected function calculate();

    /**
     * @return AbstractProcessHandler|null
     * @since 1.0.0
     */
    abstract protected function getNextHandler();
}
