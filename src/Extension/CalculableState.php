<?php


namespace Wirecard\Order\State\Extension;

use Wirecard\Order\State\State;

/**
 * Interface CalculableState stands for a state which can be calculable.
 * @package Wirecard\Order\State
 *
 * This interface simply aggregates the two implementees, StateTransition and State.
 *
 * The user does not normally implement this directly, but through CustomStateFoundation.
 */
interface CalculableState extends StateTransitions, State
{

}
