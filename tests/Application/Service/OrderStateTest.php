<?php


namespace Wirecard\Test\Application\Service;

use PHPUnit\Framework\TestCase;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState\Authorized;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState\Failed;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState\Pending;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState\Processing;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState\Started;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\OrderStateMapper;

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

    /** @var \PHPUnit_Framework_MockObject_MockObject | OrderStateMapper */
    private $mapper;

    protected function setUp()
    {
        $this->mapper = $this->getMockBuilder(OrderStateMapper::class)->setMethods(['map'])->getMock();
        $this->mapper->expects($this->any())->method('map')->willReturn($this->getSampleMapper());
    }

    /**
     * @return array
     */
    private function getSampleMapper()
    {
        return [
            self::EXTERNAL_ORDER_STATE_STARTED => new Started(),
            self::EXTERNAL_ORDER_STATE_PENDING => new Pending(),
            self::EXTERNAL_ORDER_STATE_FAILED => new Failed(),
            self::EXTERNAL_ORDER_STATE_AUTHORIZED => new Authorized(),
            self::EXTERNAL_ORDER_STATE_PROCESSING => new Processing(),
        ];
    }

    public function inputDtoDataProvider()
    {

        yield [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_FAILURE,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_FAILED
        ];

        yield [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING
        ];

        yield [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield [
            Constant::PROCESS_TYPE_RETURN,
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
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueException
     */
    public function testProcess($processType, $transactionState, $transactionType, $currentOrderState, $expectedState)
    {
        $orderStateService = new OrderState($this->mapper);

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
