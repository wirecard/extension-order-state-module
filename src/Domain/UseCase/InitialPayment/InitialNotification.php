<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\NotificationHandler;

/**
 * Class InitialNotification
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\Process
 * @since 1.0.0
 */
class InitialNotification extends AbstractProcess
{
    /**
     * @return string
     */
    public function getType()
    {
        return Constant::PROCESS_TYPE_INITIAL_NOTIFICATION;
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData|InitialProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    protected function createProcessData()
    {
        return new InitialProcessData($this->input, $this->mapper);
    }

    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function createHandler()
    {
        return new NotificationHandler($this->createProcessData());
    }
}
