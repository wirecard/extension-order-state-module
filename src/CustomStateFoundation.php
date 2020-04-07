<?php


namespace Wirecard\Order\State;


use Wirecard\Order\State\Implementation\State\CalculableState;
use Wirecard\Order\State\Implementation\State\StateHelper;
use Wirecard\Order\State\Implementation\TransitionData;

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