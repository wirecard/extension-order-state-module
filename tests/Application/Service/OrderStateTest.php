<?php


namespace Wirecard\Test\Application\Service;

use PHPUnit\Framework\TestCase;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MapDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;

/**
 * Class ConfigFactoryTest
 * @package CredentialsTest\Reader
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Application\Service\OrderState
 * @SuppressWarnings(PHPMD.LongVariable)
 * @since 1.0.0
 */
class OrderStateTest extends TestCase
{
    const EXTERNAL_ORDER_STATE_AUTHORIZED = "authorized";
    const EXTERNAL_ORDER_STATE_STARTED = "started";
    const EXTERNAL_ORDER_STATE_PENDING = "pending";
    const EXTERNAL_ORDER_STATE_PROCESSING = "processing";
    const EXTERNAL_ORDER_STATE_FAILED = "failed";

    /** @var \PHPUnit_Framework_MockObject_MockObject | MapDefinition */
    private $mapDefinition;

    protected function setUp()
    {
        $this->mapDefinition = $this->getMockBuilder(MapDefinition::class)->setMethods(['map'])->getMock();
        $this->mapDefinition->expects($this->any())->method('map')->willReturn($this->getSampleMapper());
    }

    /**
     * @return array
     */
    private function getSampleMapper()
    {
        return [
            self::EXTERNAL_ORDER_STATE_STARTED => Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING => Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_FAILED => Constant::ORDER_STATE_FAILED,
            self::EXTERNAL_ORDER_STATE_AUTHORIZED => Constant::ORDER_STATE_AUTHORIZED,
            self::EXTERNAL_ORDER_STATE_PROCESSING => Constant::ORDER_STATE_PROCESSING,
        ];
    }

    public function inputDtoDataProvider()
    {

        yield [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_STARTED
        ];

        yield [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_FAILURE,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_FAILED
        ];

        yield [
            Constant::PROCESS_TYPE_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield [
            Constant::PROCESS_TYPE_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield [
            Constant::PROCESS_TYPE_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZE,
            Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_AUTHORIZED
        ];
    }

    /**
     * @group integration
     * @small
     * @covers ::process
     * @dataProvider inputDtoDataProvider
     *
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @param string $expectedState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidProcessTypeException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testProcess($processType, $transactionState, $transactionType, $currentOrderState, $expectedState)
    {
        $mapper = new GenericOrderStateMapper($this->mapDefinition);
        $orderStateService = new OrderState($mapper);

        /** @var InputDataTransferObject | \PHPUnit_Framework_MockObject_MockObject $inputDTO */
        $inputDTO = $this->getMockBuilder(InputDataTransferObject::class)->setMethods([
            'getProcessType',
            'getTransactionState',
            'getTransactionType',
            'getCurrentOrderState'
        ])->getMock();

        $inputDTO->expects($this->any())->method('getProcessType')->willReturn($processType);
        $inputDTO->expects($this->any())->method('getTransactionState')->willReturn($transactionState);
        $inputDTO->expects($this->any())->method('getTransactionType')->willReturn($transactionType);
        $inputDTO->expects($this->any())->method('getCurrentOrderState')->willReturn($currentOrderState);

        $this->assertEquals($expectedState, $orderStateService->process($inputDTO));
    }
}
