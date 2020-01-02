<?php
declare(strict_types=1);

namespace Fns\Contracts;

interface SetTimeoutHandler
{
    public function setTimeoutStrategy(TimeoutStrategyHandler $strategyHandler);
}
