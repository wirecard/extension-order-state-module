<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase;

use Codeception\Stub\Expected;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;
use Wirecard\ExtensionOrderStateModule\Test\Support\UnitTester;

/**
 * Class AbstractProcessTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcess
 * @since 1.0.0
 */
class AbstractProcessTest extends \Codeception\Test\Unit
{
    use MockCreator;

    const TYPE_INITIAL = "initial";

    /** @var UnitTester */
    protected $tester;

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
        /** @var AbstractProcess | \PHPUnit\Framework\MockObject\MockObject $processHandler */
        $this->process = $this->getMockForAbstractClass(
            AbstractProcess::class,
            [$this->getSampleDTO(), $this->getSampleMapper()],
            "",
            true,
            true,
            true,
            ['getType', 'createProcessData', 'createHandler']
        );
    }

    /**
     * @return object
     * @throws \Exception
     */
    protected function getSampleDTO()
    {
        return $this->createDummyInputDTO(
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PENDING
        );
    }

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Exception
     */
    protected function getSampleProcessData()
    {
        return \Codeception\Stub::makeEmpty(ProcessData::class, [
            'getOrderState' => new OrderState(Constant::ORDER_STATE_PENDING),
            'getTransactionType' => new TransactionType(Constant::TRANSACTION_TYPE_PURCHASE),
            'getTransactionState' => new TransactionState(Constant::TRANSACTION_STATE_SUCCESS),
        ]);
    }

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     *
     */
    protected function getSampleMapper()
    {
        /** @var MappingDefinition $mappingDefinition */
        $mappingDefinition = \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => Expected::atLeastOnce([
                "x" => Constant::ORDER_STATE_STARTED,
                "y" => Constant::ORDER_STATE_PENDING,
            ])
        ]);
        return new GenericOrderStateMapper($mappingDefinition);
    }

    /**
     * @group unit
     * @small
     * @covers ::getType
     */
    public function testType()
    {
        $this->process->method('getType')->willReturn(self::TYPE_INITIAL);
        $this->assertEquals(self::TYPE_INITIAL, $this->process->getType());
        $this->assertTrue(is_string($this->process->getType()));
    }

    /**
     * @group unit
     * @small
     * @covers ::createProcessData
     * @covers ::__construct
     * @throws \ReflectionException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function testCreateProcessData()
    {
        $this->process->method('createProcessData')->willReturn($this->getSampleProcessData());
        $reflection = new \ReflectionClass($this->process);
        $reflectionMethod = $reflection->getMethod('createProcessData');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->process);
        $this->assertEquals($this->getSampleProcessData(), $result);
        $this->assertInstanceOf(ProcessData::class, $result);
    }

    /**
     * @group unit
     * @small
     * @covers ::createHandler
     * @covers ::__construct
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function testCreateHandler()
    {
        $handler = $this->getMockForAbstractClass(
            AbstractProcessHandler::class,
            [$this->getSampleProcessData()]
        );
        $this->process->method('createHandler')->willReturn($handler);
        $this->assertEquals($handler, $this->process->createHandler());
        $this->assertInstanceOf(AbstractProcessHandler::class, $this->process->createHandler());
    }


    public function testX()
    {
        $this->tester->assertTrue(true);
    }
}
