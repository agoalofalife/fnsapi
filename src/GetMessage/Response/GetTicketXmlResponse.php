<?php
declare(strict_types=1);

namespace Fns\GetMessage\Response;

use Fns\Contracts\ResponseReceipt;
use Fns\Contracts\ResponseSendMessage;
use Fns\Receipt;
use SimpleXMLElement;

class GetTicketXmlResponse extends SimpleXMLElement implements ResponseSendMessage, ResponseReceipt
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
        return (string)$this->Result->Ticket;
    }

    public function getReceipt():Receipt
    {
        return new Receipt($this->getBody());
    }
}
