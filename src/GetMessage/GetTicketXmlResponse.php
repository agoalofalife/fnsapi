<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Fns\Contracts\ResponseSendMessage;
use SimpleXMLElement;

class GetTicketXmlResponse extends SimpleXMLElement implements ResponseSendMessage
{
    private function getXpathServerCodeString() : string
    {
        return '//tns:GetTicketResponse/tns:Result/tns:Code/text()';
    }

    private function getXpathMessageString() : string
    {
        return '//tns:GetTicketResponse/tns:Result/tns:Ticket/text()';
    }

    public function isError() : bool
    {
        return $this->getCode() !== 200;
    }

    public function getCode(): int
    {
        return (int)$this->xpath($this->getXpathServerCodeString())[0];
    }

    public function getBody(): string
    {
        return (string)$this->xpath($this->getXpathMessageString())[0];
    }
}
