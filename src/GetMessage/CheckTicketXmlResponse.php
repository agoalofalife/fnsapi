<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Fns\Contracts\ResponseSendMessage;
use SimpleXMLElement;

class CheckTicketXmlResponse extends SimpleXMLElement implements ResponseSendMessage
{
    private function getXpathServerCodeString() : string
    {
        return '//tns:CheckTicketResponse/tns:Result/tns:Code/text()';
    }

    private function getXpathMessageString() : string
    {
        return '//tns:CheckTicketResponse/tns:Result/tns:Message/text()';
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
