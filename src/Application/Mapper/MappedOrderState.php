<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Mapper;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\OrderState;

/**
 * Class MappedOrderState
 * @package Wirecard\ExtensionOrderStateModule\Application\Mapper
 * @since 1.0.0
 */
class MappedOrderState
{
    /**
     * @var OrderState
     */
    private $internalState;

    /**
     * @var mixed
     */
    private $externalState;

    public function __construct(OrderState $internalState, $externalState)
    {
        $this->internalState = $internalState;
        $this->externalState = $externalState;
    }

    /**
     * @return mixed
     */
    public function getExternalState()
    {
        return $this->externalState;
    }

    /**
     * @return OrderState
     */
    public function getInternalState()
    {
        return $this->internalState;
    }
}
