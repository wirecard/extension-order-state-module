<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData;

class AbstractProcessHandler
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
        return null;
    }
}
