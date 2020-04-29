<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class AbstractProcessTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess
 * @since 1.0.0
 */
class AbstractProcessTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var AbstractProcess | \PHPUnit\Framework\MockObject\MockObject
     */
    protected $process;


    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        $sampleDTOInput = $this->createDummyInputDTO(
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PENDING
        );
        /** @var AbstractProcess | \PHPUnit\Framework\MockObject\MockObject $processHandler */
        $this->process = $this->getMockForAbstractClass(
            AbstractProcess::class,
            [$sampleDTOInput, $this->createGenericMapper()],
            "",
            true,
            true,
            true,
            ['getType', 'createProcessData', 'createHandler']
        );
    }

    /**
     * @group unit
     * @small
     * @covers ::getType
     */
    public function testType()
    {
        $this->process->method('getType')->willReturn("initial");
        $this->assertEquals("initial", $this->process->getType());
        $this->assertTrue(is_string($this->process->getType()));
    }

    /**
     * @group unit
     * @small
     * @covers ::createProcessData
     * @covers ::__construct
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCreateProcessData()
    {
        $processData = $this->createDummyProcessData(
            Constant::ORDER_STATE_PENDING,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $this->process->method('createProcessData')->willReturn($processData);
        $reflection = new \ReflectionClass($this->process);
        $reflectionMethod = $reflection->getMethod('createProcessData');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->process);
        $this->assertEquals($processData, $result);
        $this->assertInstanceOf(ProcessData::class, $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::createHandler
     * @covers ::__construct
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testCreateHandler()
    {
        $processData = $this->createDummyProcessData(
            Constant::ORDER_STATE_PENDING,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::TRANSACTION_STATE_SUCCESS
        );
        $handler = $this->getMockForAbstractClass(AbstractProcessHandler::class, [$processData]);
        $this->process->method('createHandler')->willReturn($handler);
        $this->assertEquals($handler, $this->process->createHandler());
        $this->assertInstanceOf(AbstractProcessHandler::class, $this->process->createHandler());
    }
}
