<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class AbstractProcessHandler
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase
 * @since 1.0.0
 */
abstract class AbstractProcessHandler
{
    use DataRegistry;

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
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState|null
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
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
     * @return bool
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @since 1.0.0
     */
    protected function isSuccessTransaction()
    {
        return $this->processData->transactionInState(Constant::TRANSACTION_STATE_SUCCESS);
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState|null
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
     * @since 1.0.0
     */
    abstract protected function calculate();

    /**
     * @return AbstractProcessHandler|null
     * @since 1.0.0
     */
    abstract protected function getNextHandler();
}
