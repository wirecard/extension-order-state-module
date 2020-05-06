<?php

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData;

use Codeception\Stub\Expected;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;
use Wirecard\ExtensionOrderStateModule\Test\Support\Helper\MockCreator;

/**
 * Class PostProcessingProcessDataTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PostProcessingProcessDataTest extends \Codeception\Test\Unit
{
    use MockCreator;

    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper
     */
    protected $mapper;

    /**
     * @var PostProcessingProcessData
     */
    protected $object;


    /**
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        $this->object = $this->getMockBuilder(PostProcessingProcessData::class)
            ->disableOriginalConstructor()->getMock();
        $this->mapper = $this->createGenericMapper();
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @covers ::getOrderTotalAmount
     * @covers ::getTransactionRequestedAmount
     * @throws InvalidPostProcessDataException
     * @throws OrderStateInvalidArgumentException
     * @throws \Exception
     */
    public function testPostProcessingProcessDataConstructor()
    {
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = $this->createDummyInputPostProcessingDTO(
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            $this->mapper->toExternal($this->fromOrderStateRegistry(Constant::ORDER_STATE_PROCESSING)),
            100,
            34
        );
        $processData = new PostProcessingProcessData($inputDTO, $this->mapper);
        $this->assertInstanceOf(PostProcessingProcessData::class, $processData);
        $this->assertInstanceOf(ProcessData::class, $processData);
        $this->assertEquals(100, $processData->getOrderTotalAmount());
        $this->assertEquals(34, $processData->getTransactionRequestedAmount());
    }

    /**
     * @return \Generator
     */
    public function inputDTODataProvider()
    {
        // OrderOpenAmount | TransactionRequestedAmount
        yield "empty_amounts" => [0, 0];
        yield "negative_amounts" => [-1, -1];
        yield "negative_amounts_1" => [-1, -0];
        yield "negative_amounts_2" => [0, -1];
        yield "negative_amounts_3" => [10, -1];
        yield "negative_amounts_4" => [-11, 100];
        yield "string_amounts" => ["xxx", 100];
        yield "string_amount_1" => ["0", "0"];
        yield "string_amounts_2" => ["10", "10"];
        yield "string_amounts_3" => ["2222", "3333"];
        yield "string_bool_amounts" => [true, "3333"];
        yield "bool_amounts" => [true, false];
        yield "bool_amounts_1" => [true, true];
        yield "bool_amounts_2" => [23, 123];
    }

    /**
     * @group unit
     * @small
     * @dataProvider inputDTODataProvider
     * @covers ::loadFromInput
     * @param float $orderOpenAmount
     * @param float $transactionRequestedAmount
     * @throws \Exception
     */
    public function testLoadFromInput($orderOpenAmount, $transactionRequestedAmount)
    {
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getOrderOpenAmount' => Expected::atLeastOnce($orderOpenAmount),
                'getTransactionRequestedAmount' => Expected::atLeastOnce($transactionRequestedAmount)
            ]
        );

        $class = new \ReflectionClass($this->object);
        $method = $class->getMethod('loadFromInput');
        $method->setAccessible(true);

        $this->expectException(InvalidPostProcessDataException::class);
        $method->invokeArgs($this->object, [$inputDTO]);
    }
}
