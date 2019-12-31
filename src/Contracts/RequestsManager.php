<?php
declare(strict_types=1);

namespace Fns\Contracts;

interface RequestsManager
{
    public function isProcessFinished() : bool;

    public function executeRequest(): void;
}
