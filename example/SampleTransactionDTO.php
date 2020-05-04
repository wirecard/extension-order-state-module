<?php


namespace Wirecard\ExtensionOrderStateModule\Example;


use Wirecard\ExtensionOrderStateModule\Domain\Contract\TransactionDataTransferObject;

class SampleTransactionDTO implements TransactionDataTransferObject
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var float
     */
    private $amount;

    /**
     * SampleTransactionDTO constructor.
     * @param string $type
     * @param float $amount
     */
    public function __construct($type, $amount)
    {
        $this->type = $type;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }


}
