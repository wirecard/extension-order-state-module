<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Test\Support\Helper;

use Codeception\Stub\Expected;
use Wirecard\ExtensionOrderStateModule\Application\Mapper\GenericOrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\MappingDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\ProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\InitialProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\ProcessData\PostProcessingProcessData;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionState;
use Wirecard\ExtensionOrderStateModule\Domain\Entity\TransactionType;
use Wirecard\ExtensionOrderStateModule\Domain\Registry\DataRegistry;

/**
 * Class Creator
 * @package Wirecard\ExtensionOrderStateModule\Test\Support\Helper
 * @since 1.0.0
 */
trait MockCreator
{
    use DataRegistry;

    /**
     * @param $state
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function createOrderState($state)
    {
        return $this->fromOrderStateRegistry($state);
    }

    /**
     * @param $type
     * @return TransactionType
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     */
    public function createTransactionType($type)
    {
        return $this->fromTransactionTypeRegistry($type);
    }

    /**
     * @param $state
     * @return TransactionState
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     */
    public function createTransactionState($state)
    {
        return new TransactionState($state);
    }

    /**
     * @param $processType
     * @param $transactionState
     * @param $transactionType
     * @param $currentOrderState
     * @param $orderOpenAmount
     * @param $transactionRequestedAmount
     * @return object|InputDataTransferObject
     * @throws \Exception
     */
    public function createDummyInputDTO(
        $processType,
        $transactionState,
        $transactionType,
        $currentOrderState,
        $orderOpenAmount = 0,
        $transactionRequestedAmount = 0
    ) {
        return \Codeception\Stub::makeEmpty(
            InputDataTransferObject::class,
            [
                'getProcessType' => Expected::atLeastOnce($processType),
                'getTransactionState' => Expected::atLeastOnce($transactionState),
                'getTransactionType' => Expected::atLeastOnce($transactionType),
                'getCurrentOrderState' => Expected::atLeastOnce($currentOrderState),
                'getOrderOpenAmount' => $orderOpenAmount,
                'getTransactionRequestedAmount' => $transactionRequestedAmount,
            ]
        );
    }

    /**
     * @param string $orderState
     * @param string $transactionType
     * @param string $transactionState
     * @return object | ProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\InvalidValueObjectException
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function createDummyProcessData(
        $orderState = Constant::ORDER_STATE_STARTED,
        $transactionType = Constant::TRANSACTION_TYPE_PURCHASE,
        $transactionState = Constant::TRANSACTION_STATE_SUCCESS
    ) {
        return \Codeception\Stub::makeEmpty(ProcessData::class, [
            'getOrderState' => $this->fromOrderStateRegistry($orderState),
            'getTransactionType' => $this->fromTransactionTypeRegistry($transactionType),
            'getTransactionState' => new TransactionState($transactionState),
        ]);
    }

    /**
     * @param array $definition
     * @return object|MappingDefinition
     * @throws \Exception
     */
    public function createMappingDefinition(array $definition = [])
    {
        if (empty($definition)) {
            $definition = [
                "external_started" => Constant::ORDER_STATE_STARTED,
                "external_pending" => Constant::ORDER_STATE_PENDING,
                "external_failed" => Constant::ORDER_STATE_FAILED,
                "external_authorized" => Constant::ORDER_STATE_AUTHORIZED,
                "external_processing" => Constant::ORDER_STATE_PROCESSING,
                "external_refunded" => Constant::ORDER_STATE_REFUNDED,
                "external_partial_refunded" => Constant::ORDER_STATE_PARTIAL_REFUNDED,
            ];
        }
        return \Codeception\Stub::makeEmpty(MappingDefinition::class, [
            'definitions' => Expected::atLeastOnce($definition)
        ]);
    }

    /**
     * @param array $definition
     * @return GenericOrderStateMapper
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function createGenericMapper(array $definition = [])
    {
        return new GenericOrderStateMapper($this->createMappingDefinition($definition));
    }

    /**
     * @param array $definition
     * @param $processType
     * @param $transactionState
     * @param $transactionType
     * @param $orderState
     * @param int $orderOpenAmount
     * @param int $transactionRequestedAmount
     * @return InitialProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function createInitialProcessData(
        $orderState,
        $transactionType,
        $transactionState,
        array $definition = [],
        $orderOpenAmount = 0,
        $transactionRequestedAmount = 0
    ) {
        $mapper = new GenericOrderStateMapper($this->createMappingDefinition($definition));
        $dto = $this->createDummyInputDTO(
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            $transactionState,
            $transactionType,
            $mapper->toExternal($this->fromOrderStateRegistry($orderState)),
            $orderOpenAmount,
            $transactionRequestedAmount
        );

        return new InitialProcessData($dto, $mapper);
    }

    /**
     * @param array $definition
     * @param $processType
     * @param $transactionState
     * @param $transactionType
     * @param $orderState
     * @param int $orderOpenAmount
     * @param int $transactionRequestedAmount
     * @return InitialProcessData
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws \Exception
     */
    public function createPostProcessData(
        $orderState,
        $transactionType,
        $transactionState,
        array $definition = [],
        $orderOpenAmount = 100,
        $transactionRequestedAmount = 100
    ) {
        $mapper = new GenericOrderStateMapper($this->createMappingDefinition($definition));
        $dto = $this->createDummyInputDTO(
            Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION,
            $transactionState,
            $transactionType,
            $mapper->toExternal($this->fromOrderStateRegistry($orderState)),
            $orderOpenAmount,
            $transactionRequestedAmount
        );

        return new PostProcessingProcessData($dto, $mapper);
    }
}
