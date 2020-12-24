<?php

declare(strict_types=1);

namespace Fns\Contracts;

use Fns\Receipt;

interface ResponseReceipt
{
    public function getReceipt():Receipt;
}
