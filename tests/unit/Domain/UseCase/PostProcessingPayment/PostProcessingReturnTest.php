<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\UseCase\PostProcessingPayment;

use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\AbstractProcessHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\Handler\ReturnHandler;
use Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturn;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class PostProcessingReturnTest
 * @package Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\UseCase\PostProcessingPayment\PostProcessingReturn
 * @since 1.0.0
 */
class PostProcessingReturnTest extends \Codeception\Test\Unit
{
    use MockCreator;

    const CURRENT_ORDER_STATE = "started";

    /**
     * @var PostProcessingReturn
     */
    protected $process;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _setUp()
    {
        $inputDTO = $this->createDummyInputDTO(
            Constant::PROCESS_TYPE_POST_PROCESSING_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            self::CURRENT_ORDER_STATE,
            100,
            100
        );
        $mapper = $this->createGenericMapper([
            self::CURRENT_ORDER_STATE => Constant::ORDER_STATE_STARTED,
            "y" => Constant::ORDER_STATE_PENDING,
        ]);
        $this->process = new PostProcessingReturn($inputDTO, $mapper);
    }

    /**
     * @group unit
     * @small
     * @covers ::getType
     */
    public function testType()
    {
        $this->assertEquals(Constant::PROCESS_TYPE_POST_PROCESSING_RETURN, $this->process->getType());
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
        $this->assertInstanceOf(PostProcessingProcessData::class, $processData);
    }

    /**
     * @group unit
     * @small
     * @covers ::createHandler
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException
     */
    public function testCreateHandler()
    {
        $handler = $this->process->createHandler();
        $this->assertInstanceOf(ReturnHandler::class, $handler);
        $this->assertInstanceOf(AbstractProcessHandler::class, $handler);
        $this->assertNotNull($handler);
    }
}
