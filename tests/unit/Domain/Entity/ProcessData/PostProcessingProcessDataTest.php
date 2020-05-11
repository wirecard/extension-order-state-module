<?php

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData;

use Codeception\Stub\Expected;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidPostProcessDataException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;

/**
 * Class PostProcessingProcessDataTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PostProcessingProcessDataTest extends \Codeception\Test\Unit
{
    const EXTERNAL_ORDER_STATE_AUTHORIZED = "external_authorized";
    const EXTERNAL_ORDER_STATE_STARTED = "external_started";
    const EXTERNAL_ORDER_STATE_PENDING = "external_pending";
    const EXTERNAL_ORDER_STATE_PROCESSING = "external_processing";
    const EXTERNAL_ORDER_STATE_FAILED = "external_failed";
    const EXTERNAL_ORDER_STATE_REFUNDED = "external_refunded";
    const EXTERNAL_ORDER_STATE_PARTIAL_REFUNDED = "external_partial_refunded";

    /**
     * @var \Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper
     */
    protected $mapper;

    /**
     * @var PostProcessingProcessData
     */
    protected $object;

    /**
     * @return array
     * @since 1.0.0
     */
    private function getSampleMapDefinition()
    {
        return [
            self::EXTERNAL_ORDER_STATE_STARTED => Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING => Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_FAILED => Constant::ORDER_STATE_FAILED,
            self::EXTERNAL_ORDER_STATE_AUTHORIZED => Constant::ORDER_STATE_AUTHORIZED,
            self::EXTERNAL_ORDER_STATE_PROCESSING => Constant::ORDER_STATE_PROCESSING,
        ];
    }

    /**
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _before()
    {
        /** @var \Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition $mapDefinition */
        $mapDefinition = \Codeception\Stub::makeEmpty(
            \Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition::class,
            [
                'definitions' => $this->getSampleMapDefinition()
            ]
        );
        $this->mapper = new GenericOrderStateMapper($mapDefinition);

        $this->object = $this->getMockBuilder(PostProcessingProcessData::class)
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @covers ::getOrderOpenAmount
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
        $inputDTO = \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getProcessType' => Expected::atLeastOnce(Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION),
                'getTransactionState' => Expected::atLeastOnce(Constant::TRANSACTION_STATE_SUCCESS),
                'getTransactionType' => Expected::atLeastOnce(Constant::TRANSACTION_TYPE_PURCHASE),
                'getCurrentOrderState' => Expected::atLeastOnce(self::EXTERNAL_ORDER_STATE_PROCESSING),
                'getOrderOpenAmount' => Expected::atLeastOnce(100),
                'getTransactionRequestedAmount' => Expected::atLeastOnce(34)
            ]
        );
        $processData = new PostProcessingProcessData($inputDTO, $this->mapper);
        $this->assertInstanceOf(PostProcessingProcessData::class, $processData);
        $this->assertInstanceOf(ProcessData::class, $processData);
        $this->assertEquals(100, $processData->getOrderOpenAmount());
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
