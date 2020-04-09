<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Interfaces;


interface OrderStateMapper
{

    /**
     * @throws @todo exhaustive executioner
     */
    public function mapToInternal(OrderState $state);

    /**
     * @throws @todo add
     */
    public function mapToExternal(OrderState $state);
}