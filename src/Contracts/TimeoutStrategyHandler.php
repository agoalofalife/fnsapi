<?php
declare(strict_types=1);

namespace Fns\Contracts;

interface TimeoutStrategyHandler
{
    public function handleTimeout();
}