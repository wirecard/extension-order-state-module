<?php

use Test\Double\Stub\OrderStub;
use Test\Double\Stub\ShopSystemStub;
use Wirecard\Order\State\CreditCardTransactionType\AuthorizationTransaction;
use Wirecard\Order\State\CreditCardTransactionType;
use Wirecard\Order\State\CreditCardTransactionType\PurchaseTransaction;
use Wirecard\Order\State\State\Authorized;
use Wirecard\Order\State\State\Failed;
use Wirecard\Order\State\State\Pending;
use Wirecard\Order\State\State\Processing;
use Wirecard\Order\State\State\Started;
use Wirecard\Order\State\State\Success;
use Wirecard\Order\State\OrderState;
use Wirecard\Order\State\State;
use Wirecard\Order\State\TransactionType;

class FacadeTest extends PHPUnit_Framework_TestCase
{
    public function simple_cases_provider()
    {
        return [
            [//case 0
                new PurchaseTransaction(),//back-end is set to purchase
                new TransactionType\None(),//there has been no interaction with the engine yet
                new Started(),//the current state of the order is started
                new Pending(),//the desired new state is pending
                "backend CC=purchase, engine=none, order state=started, desired state=pending"
            ],
            [// case 1: happens after case 0 on the happy path
                new PurchaseTransaction(),
                new TransactionType\Success(),
                new Pending(),
                new Success(),
                "backend CC=purchase, engine=success, order state=pending, desired state=success"
            ],
            [//case 2: failure alternative to case 1
                new PurchaseTransaction(),
                new TransactionType\Failure(),//the engine said it's a failure
                new Pending(),
                new Failed(),//so we expect the order to go to failed
                "backend CC=purchase, engine=failure, order state=pending, desired state=failed"
            ],
            [//case 3: initial authorization transaction, like 0, but a different back-end setting
                new AuthorizationTransaction(),//back-end is set to authorization
                new TransactionType\None(),//there has been no interaction with the engine yet
                new Started(),//the current state of the order is started
                new Pending(),//the desired new state is pending
                "backend CC=authorization, engine=none, order state=started, desired state=pending"
            ],
            [//case 4: happens after case 3 on the happy path
                new AuthorizationTransaction(),
                new TransactionType\Success(),
                new Pending(),
                new Success(),
                "backend CC=authorization, engine=success, order state=pending, desired state=success"
            ],
            [//case 5: alternative to case 4, for the failure scenario
                new AuthorizationTransaction(),
                new TransactionType\Failure(),
                new Pending(),
                new Failed(),
                "backend CC=authorization, engine=failure, order state=pending, desired state=failed"
            ],
            [//case 6: success goes into processing, next step on the happy path after case 1
                new PurchaseTransaction(),
                new TransactionType\Success(),
                new Success(),
                new Processing(),
                "backend CC=purchase, engine=success, order state=success, desired state=processing"
            ],
            [//case 7: success goes into processing, next step on the happy path after case 1
                new AuthorizationTransaction(),
                new TransactionType\Success(),
                new Success(),
                new Authorized(),
                "backend CC=authorization, engine=success, order state=success, desired state=authorized"
            ],
        ];
    }

    /**
     * @test Tests what should happen
     * @dataProvider simple_cases_provider
     * @param CreditCardTransactionType $creditCardTransactionType
     * @param TransactionType $engineTransactionType
     * @param State $currentOrderState
     * @param State $expected
     * @param string $message
     */
    public function all_simple_combinations(
        CreditCardTransactionType $creditCardTransactionType,//backend setting for CC
        TransactionType $engineTransactionType,//what the engine has said about this so far
        State $currentOrderState,//the current state of the order
        State $expected,//the desired state of the order after the transition
        $message
    ) {//message, in case the assertion fails
        $shopSystem = new ShopSystemStub($creditCardTransactionType);
        $order = new OrderStub($currentOrderState, $engineTransactionType);

        $module = new OrderState($shopSystem);
        $newState = $module->getNextState($order);
        //$order->changeState($newState);

        $this->assertTrue($expected->equals($newState), $message . ' Got instead: '.get_class($newState));
    }

    public function reversed_simple_cases_provider()
    {
        return [

        ];
    }

    /**
     * Tests what should not happend
     * @dataProvider reversed_simple_cases_provider
     * @todo depends on the StateRegistry
     */
    public function reverse_simple_combinations()
    {
    }
}
