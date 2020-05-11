<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\InitialPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\Handler\NotificationHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class InitialNotificationTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\InitialPayment
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\InitialPayment\InitialNotification
 * @since 1.0.0
 */
class InitialNotificationTest extends \Codeception\Test\Unit
{
    use MockCreator;

    const CURRENT_ORDER_STATE = "x";

    /**
     * @var InitialNotification
     */
    protected $process;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _setUp()
    {
        $this->process = new InitialNotification($this->createDummyInputDTO(
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            self::CURRENT_ORDER_STATE
        ), $this->createGenericMapper([
            self::CURRENT_ORDER_STATE => Constant::ORDER_STATE_STARTED,
            "y" => Constant::ORDER_STATE_PENDING,
        ]));
    }

    /**
     * @group unit
     * @small
     * @covers ::getType
     */
    public function testType()
    {
        $this->assertEquals(Constant::PROCESS_TYPE_INITIAL_NOTIFICATION, $this->process->getType());
        $this->assertTrue(is_string($this->process->getType()));
    }

    /**
     * @group unit
     * @small
     * @covers ::createProcessData
     * @throws \ReflectionException
     */
    public function testCreateProcessData()
    {
        $reflectionMethod = new \ReflectionMethod($this->process, "createProcessData");
        $reflectionMethod->setAccessible(true);
        $processData = $reflectionMethod->invoke($this->process);
        $this->assertInstanceOf(ProcessData::class, $processData);
        $this->assertInstanceOf(InitialProcessData::class, $processData);
    }

    /**
     * @group unit
     * @small
     * @covers ::createHandler
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCreateHandler()
    {
        $handler = $this->process->createHandler();
        $this->assertInstanceOf(NotificationHandler::class, $handler);
        $this->assertInstanceOf(AbstractProcessHandler::class, $handler);
        $this->assertNotNull($handler);
    }
}
