<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Application\Mapper;

use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\MapDefinition;
use Wirecard\ExtensionOrderStateModule\Domain\Interfaces\OrderStateMapper;

/**
 * Class GenericOrderStateMapper
 * @package Wirecard\ExtensionOrderStateModule\Application\Mapper
 */
class GenericOrderStateMapper implements OrderStateMapper
{
    /**
     * @var MapDefinition
     */
    private $definition;

    /**
     * GenericOrderStateMapper constructor.
     * @param MapDefinition $definition
     * @since 1.0.0
     */
    public function __construct(MapDefinition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return array
     */
    public function map()
    {
        return [];
    }
}
