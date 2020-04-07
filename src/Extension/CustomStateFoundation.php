<?php


namespace Wirecard\Order\State\Extension;


use Wirecard\Order\State\Extension\CalculableState;
use Wirecard\Order\State\Implementation\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;
use Wirecard\Order\State\State;

abstract class CustomStateFoundation implements CalculableState
{
    use StateHelper;

    abstract public function getPossibleNextStates();

    abstract public function getNextState(TransitionData $transitionData);

    /**
     * @param State[] $haystack
     * @param State $needle
     * @param State $replacement
     * @return State[]
     */
    protected function replaceState($haystack, State $needle, State $replacement)
    {
        $match = -1;
        foreach ($haystack as $pos => $item) {
            if ($item->equals($needle)) {
                $match = $pos;
                break;
            }
        }
        if ($match != -1) {
            $haystack[$match] = $replacement;
        }

        return $haystack;
    }

}