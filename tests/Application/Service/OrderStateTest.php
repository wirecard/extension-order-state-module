<?php


namespace Wirecard\Test\Application\Service;

use PHPUnit\Framework\TestCase;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Application\Service\OrderState;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;
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

    /** @var \PHPUnit_Framework_MockObject_MockObject | MappingDefinition */
    private $mapDefinition;

    /**
     * @var OrderState
     */
    private $orderState;

    /**
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    protected function setUp()
    {
        $this->mapDefinition = $this->getMockBuilder(MappingDefinition::class)->setMethods(['definitions'])->getMock();
        $this->mapDefinition->expects($this->any())->method('definitions')->willReturn($this->getSampleMapper());
        $this->orderState = new OrderState(new GenericOrderStateMapper($this->mapDefinition));
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

    /**
     * GRAMMAR: {TransactionType}_{CurrentOrderState}_{TransactionState}_{ProcessType}_{NextOrderState}
     *
     * TransactionType: Payment type,
     * CurrentOrderState: Current order state,
     * TransactionState: Transaction state,
     * ProcessType: Process type,
     * NextOrderState: Next order state
     *
     * Example
     * debit_started_success_initial_return_pending
     *
     * Explanation:
     * Order is currently in state{CurrentOrderState} "started" and was paid with Debit(TransactionType)
     * Transaction{TransactionState} request to gateway was successful.
     * Response came during initial return / notification
     * Next order state should be: Pending
     *
     * @return \Generator
     */
    public function inputDtoDataProvider()
    {
        yield "debit_started_success_initial_return_pending" => [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PENDING
        ];

        yield "debit_started_failure_initial_return_failed" => [
            Constant::PROCESS_TYPE_RETURN,
            Constant::TRANSACTION_STATE_FAILURE,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_FAILED
        ];

        yield "debit_started_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            Constant::ORDER_STATE_STARTED,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield "purchase_pending_success_initial_notification_processing" => [
            Constant::PROCESS_TYPE_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            Constant::ORDER_STATE_PENDING,
            self::EXTERNAL_ORDER_STATE_PROCESSING
        ];

        yield "authorization_pending_success_initial_notification_processing" => [
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
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException
     */
    public function testProcess($processType, $transactionState, $transactionType, $currentOrderState, $expectedState)
    {
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

        $this->assertEquals($expectedState, $this->orderState->process($inputDTO));
    }

    /**
     * @group integration
     * @small
     * @covers ::__construct
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @expectedException \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function testFailingConstructor()
    {
        $mapping = array_merge($this->getSampleMapper(), ['X' => 'INVALID_ORDER_STATE_TYPE']);
        $this->mapDefinition = $this->getMockBuilder(MappingDefinition::class)->setMethods(['definitions'])->getMock();
        $this->mapDefinition->expects($this->any())->method('definitions')->willReturn($mapping);
        new OrderState(new GenericOrderStateMapper($this->mapDefinition));
    }
}
