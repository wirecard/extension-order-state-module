<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Service;

use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess;

/**
 * Class ProcessHandlerService
 * @package Wirecard\ExtensionOrderStateModule\Domain\Service
 * @since 1.0.0
 */
class ProcessHandlerService
{
    /**
     * @var AbstractProcess
     */
    private $process;

    /**
     * ProcessHandlerService constructor.
     * @param AbstractProcess $process
     */
    public function __construct(AbstractProcess $process)
    {
        $this->process = $process;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     * @throws IgnorableStateException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorablePostProcessingFailureException
     */
    public function handle()
    {
        $handler = $this->process->createHandler();
        $orderState = $handler->handle();
        if (null === $orderState) {
            throw new IgnorableStateException("State is ignored!");
        }
        return $orderState;
    }
}
