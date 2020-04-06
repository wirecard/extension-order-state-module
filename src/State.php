<?php


namespace Wirecard\Order\State;

use Wirecard\Order\State\Implementation\TransitionData;

interface State
{

    public function equals(State $other);
}
