<?php
declare(strict_types=1);

namespace Fns\GetMessage\Response;

use Fns\Contracts\ResponseSendMessage;
use SimpleXMLElement;

class CheckTicketXmlResponse extends SimpleXMLElement implements ResponseSendMessage
{
    public function isError(): bool
    {
        return $this->getCode() !== 200;
    }

    public function getCode(): int
    {
        return (int)$this->Result->Code;
    }

    public function getBody(): string
    {
        return (string)$this->Result->Message;
    }
}
