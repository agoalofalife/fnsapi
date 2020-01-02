<?php
declare(strict_types=1);

namespace Fns\Contracts;

interface ResponseSendMessage
{
    public function getCode() : int;

    public function isError() : bool;

    public function getBody() : string;
}
