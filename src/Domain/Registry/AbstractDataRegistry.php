<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-order-state-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-order-state-module/blob/master/LICENSE
 */

namespace Wirecard\ExtensionOrderStateModule\Domain\Registry;

use Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException;

/**
 * Class AbstractRegistry
 * @package Wirecard\ExtensionOrderStateModule\Domain\Registry
 * @since 1.0.0
 */
abstract class AbstractDataRegistry
{
    /**
     * @var array
     */
    protected $container = [];

    /**
     * AbstractDataRegistry constructor.
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @since 1.0.0
     */
    abstract protected function init();

    /**
     * @param string $key
     * @param object|mixed $object
     * @return AbstractDataRegistry
     * @since 1.0.0
     */
    protected function attach($key, $object)
    {
        if (!empty($key)) {
            $this->container[$key] = $object;
        }

        return $this;
    }

    /**
     * @param string $key
     * @return object
     * @throws NotInRegistryException
     * @since 1.0.0
     */
    public function get($key)
    {
        if (!isset($this->container[$key])) {
            throw new NotInRegistryException("Reference [{$key}] isn't registered in scope");
        }
        return $this->container[$key];
    }
}
