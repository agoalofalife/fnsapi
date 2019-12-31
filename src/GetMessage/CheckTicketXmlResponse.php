<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use SimpleXMLElement;

class CheckTicketXmlResponse extends SimpleXMLElement
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
        return ((int)$this->xpath($this->getXpathServerCodeString())[0] !== 200 &&
            (string)$this->xpath($this->getXpathMessageString())[0] !== 'Отправленные данные корректны');
    }

    public function isCheckExist() : bool
    {
        return $this->isError() === false;
    }
}
