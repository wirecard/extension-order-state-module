<?php

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData;

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
     * @covers ::loadFromInput
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
            34,
            50,
            50
        );
        $processData = new PostProcessingProcessData($inputDTO, $this->mapper, $this->getDefaultPrecision());
        $this->assertInstanceOf(PostProcessingProcessData::class, $processData);
        $this->assertInstanceOf(ProcessData::class, $processData);
        $this->assertEquals(100, $processData->getOrderTotalAmount());
        $this->assertEquals(34, $processData->getTransactionRequestedAmount());
        $this->assertEquals(50, $processData->getOrderCapturedAmount());
        $this->assertEquals(50, $processData->getOrderRefundedAmount());
    }

    /**
     * @return \Generator
     */
    public function inputDTODataProvider()
    {
        // OrderTotalAmount | TransactionRequestedAmount | OrderCapturedAmount | OrderRefundedAmount
        yield "all_inputs_invalid" => [0, 0, 0, 0];
        yield "negative_amounts" => [-1, -1, -1, -1];
        yield "total_is_invalid" => [0, 10, 10, 10];
        yield "total_is_negative" => [-330, 10, 10, 10];
        yield "requested_amount_is_invalid" => [1110, -111, 123, 123];
        yield "requested_amount_is_invalid_1" => [1110, 0, 123, 123];
        yield "order refunded is over capture" => [300, 100, 100, 499];
        yield "string_amounts" => ["xxx", "xxx", "xxx", "xxx"];
        yield "requested_amount_over_order_amount" => [100, 400, 0, 0];
    }

    /**
     * @group unit
     * @small
     * @dataProvider inputDTODataProvider
     * @covers ::validate
     * @covers ::validateOrderRefundedAmount
     * @covers ::validateOrderCapturedAmount
     * @covers ::validateTransactionRequestedAmount
     * @covers ::validateOrderTotalAmount
     * @param float $orderTotalAmount
     * @param float $transactionRequestedAmount
     * @param float $captureAmount
     * @param float $refundAmount
     * @throws InvalidPostProcessDataException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function testValidate($orderTotalAmount, $transactionRequestedAmount, $captureAmount, $refundAmount)
    {
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = $this->createDummyInputPostProcessingDTO(
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            $this->mapper->toExternal($this->fromOrderStateRegistry(Constant::ORDER_STATE_PROCESSING)),
            $orderTotalAmount,
            $transactionRequestedAmount,
            $captureAmount,
            $refundAmount
        );



        $this->expectException(InvalidPostProcessDataException::class);
        new PostProcessingProcessData($inputDTO, $this->mapper, $this->getDefaultPrecision());
    }
}
