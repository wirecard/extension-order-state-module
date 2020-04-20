<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;

abstract class AbstractProcessHandler
{
    /**
     * @var ProcessData
     */
    protected $processData;

    /**
     * AbstractProcessHandler constructor.
     * @param ProcessData $processData
     */
    public function __construct(ProcessData $processData)
    {
        $this->processData = $processData;
    }

    /**
     * @return null|\Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
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
     */
    abstract protected function calculate();

    /**
     * @return AbstractProcessHandler|null
     * @since 1.0.0
     */
    abstract protected function getNextHandler();
}
