<?php
declare(strict_types=1);

namespace Fns;

use SimpleXMLElement;

class AuthXmlResponse extends SimpleXMLElement
{
    private function getXpathFaultString() : string
    {
        return '//tns:AuthResponse/tns:Fault/tns:Message/text()';
    }

    private function getXpathTokenString() : string
    {
        return  '//tns:AuthResponse/tns:Result/tns:Token/text()';
    }

    private function getXpathExpireTimeString() : string
    {
        return  '//tns:AuthResponse/tns:Result/tns:ExpireTime/text()';
    }

    public function isError() : bool
    {
        return !empty($this->xpath($this->getXpathFaultString()));
    }

    public function getToken() : string
    {
        return (string)$this->xpath($this->getXpathTokenString())[0];
    }

    public function getTime() : string
    {
        return (string)$this->xpath($this->getXpathExpireTimeString())[0];
    }
}