<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData;

use Codeception\Stub\Expected;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;

/**
 * Class InitialProcessDataTest
 * @package Wirecard\ExtensionOrderStateModule\Test\Unit\Domain\Entity\ProcessData
 * @coversDefaultClass \Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData
 * @since 1.0.0
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InitialProcessDataTest extends \Codeception\Test\Unit
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
        /** @var \PHPUnit\Framework\MockObject\MockObject $mapDefinition */
        $mapDefinition = \Codeception\Stub::makeEmpty(
            \Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition::class,
            [
                'definitions' => $this->getSampleMapDefinition()
            ]
        );
        $this->mapper = new GenericOrderStateMapper($mapDefinition);
    }

    /**
     * @return \Generator
     */
    public function inputDTODataProvider()
    {
        yield "debit_started_success_initial_return" => [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            self::EXTERNAL_ORDER_STATE_STARTED,
        ];

        yield "purchase_processing_failure_pp_notification" => [
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            Constant::TRANSACTION_STATE_FAILED,
            Constant::TRANSACTION_TYPE_PURCHASE,
            self::EXTERNAL_ORDER_STATE_PROCESSING,
        ];

        yield "debit_started_success_initial_notification" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            self::EXTERNAL_ORDER_STATE_STARTED,
        ];

        yield "purchase_pending_success_initial_notification" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_PURCHASE,
            self::EXTERNAL_ORDER_STATE_PENDING,
        ];

        yield "authorization_pending_success_initial_notification" => [
            Constant::PROCESS_TYPE_INITIAL_NOTIFICATION,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_AUTHORIZE,
            self::EXTERNAL_ORDER_STATE_PENDING,
        ];
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @covers ::getTransactionState
     * @covers ::getTransactionType
     * @covers ::getOrderState
     * @covers ::transactionInType
     * @covers ::transactionTypeInRange
     * @covers ::transactionInState
     * @covers ::orderInState
     * @dataProvider inputDTODataProvider
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @throws \Exception
     */
    public function testInitialProcessData(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState
    ) {
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getProcessType' => Expected::atLeastOnce($processType),
                'getTransactionState' => Expected::atLeastOnce($transactionState),
                'getTransactionType' => Expected::atLeastOnce($transactionType),
                'getCurrentOrderState' => Expected::atLeastOnce($currentOrderState),
                'getOrderOpenAmount' => Expected::atLeastOnce(0),
                'getTransactionRequestedAmount' => Expected::atLeastOnce(0)
            ]
        );

        $mapper = $this->mapper;
        $currentOrderState = $mapper->toInternal($currentOrderState);
        $processData = new InitialProcessData($inputDTO, $mapper);
        $this->assertInstanceOf(ProcessData::class, $processData);
        $this->assertInstanceOf(InitialProcessData::class, $processData);
        $this->assertEquals($currentOrderState, $processData->getOrderState());
        $this->assertEquals(
            new TransactionType($transactionType),
            $processData->getTransactionType()
        );
        $this->assertEquals(new TransactionState($transactionState), $processData->getTransactionState());
        $this->assertTrue($processData->orderInState((string)$currentOrderState));
        $this->assertTrue($processData->transactionInState($inputDTO->getTransactionState()));
        $this->assertTrue($processData->transactionInType($inputDTO->getTransactionType()));
        $this->assertTrue($processData->transactionTypeInRange([$inputDTO->getTransactionType()]));
    }

    /**
     * @return \Generator
     */
    public function inputDTOInvalidDataProvider()
    {
        yield [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            "INVALID_TRANSACTION_STATE",
            Constant::TRANSACTION_TYPE_DEBIT,
            self::EXTERNAL_ORDER_STATE_STARTED
        ];

        yield [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            "INVALID_TRANSACTION_TYPE",
            self::EXTERNAL_ORDER_STATE_STARTED
        ];

        yield [
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            Constant::TRANSACTION_STATE_SUCCESS,
            Constant::TRANSACTION_TYPE_DEBIT,
            "INVALID_EXTERNAL_TYPE"
        ];
    }

    /**
     * @group unit
     * @small
     * @covers ::__construct
     * @dataProvider inputDTOInvalidDataProvider
     * @param string $processType
     * @param string $transactionState
     * @param string $transactionType
     * @param string $currentOrderState
     * @throws \Exception
     */
    public function testFailingInitialProcessData(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState
    ) {
        /**
         * @var InputDataTransferObject $inputDTO
         */
        $inputDTO = \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getProcessType' => Expected::atLeastOnce($processType),
                'getTransactionState' => Expected::atLeastOnce($transactionState),
                'getTransactionType' => Expected::atLeastOnce($transactionType),
                'getCurrentOrderState' => Expected::atLeastOnce($currentOrderState),
                'getOrderOpenAmount' => Expected::atLeastOnce(0),
                'getTransactionRequestedAmount' => Expected::atLeastOnce(0)
            ]
        );
        $this->expectException(
            \Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException::class
        );
        new InitialProcessData($inputDTO, $this->mapper);
    }
}
