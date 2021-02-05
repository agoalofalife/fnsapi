<?php
declare(strict_types=1);

namespace Fns\Auth;

use SimpleXMLElement;

class AuthXmlResponse extends SimpleXMLElement
{
    private function getXpathFaultString() : string
    {
        return '//ns2:AuthResponse/ns2:Fault/ns2:Message/text()';
    }

    private function getXpathTokenString() : string
    {
        return  '//ns2:AuthResponse/ns2:Result/ns2:Token/text()';
    }

    private function getXpathExpireTimeString() : string
    {
        return  '//ns2:AuthResponse/ns2:Result/ns2:ExpireTime/text()';
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
