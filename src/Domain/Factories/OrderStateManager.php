<?php


namespace Wirecard\ExtensionOrderStateModule\Domain\Factories;


use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;
use Wirecard\ExtensionOrderStateModule\Domain\UseCases\InitialPayment\ReturnOrderStateManager;

class OrderStateManager
{
    /**
     * @return \Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateManager
     */
    public function create() {
        /**
         *  classes that can be returned are:
         * InitialReturn
         * InitialNotify
         *
         * ReferencingReturn
         * ReferencingNotify
         */
        return new ReturnOrderStateManager();
    }
}