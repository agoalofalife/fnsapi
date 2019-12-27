<?php
declare(strict_types=1);

namespace Fns;

use Psr\SimpleCache\CacheInterface;
use SoapClient;
use UnexpectedValueException;

class AuthRequest
{
    private $wsdl = 'https://openapi.nalog.ru:8090/open-api/AuthService/0.1?wsdl';
    private $client;
    private $masterToken;
    private $xmlResponse;
    private $storage;
    private $nameToken = 'temp_token';

    public function __construct(string $masterToken, CacheInterface $cache)
    {
        $this->masterToken = $masterToken;
        $this->client = new SoapClient($this->wsdl);
        $this->storage = $cache;
    }

    private function exceptionGetToken()
    {
        throw new UnexpectedValueException('Temp token has not got');
    }

    private function getBodyXml() : array
    {
        return [[
            'Message' => [
                'any' => "<tns:AuthRequest xmlns:tns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0\">
	<tns:AuthAppInfo>
		<tns:MasterToken>{$this->masterToken}</tns:MasterToken>
	</tns:AuthAppInfo>
</tns:AuthRequest>"
            ]
        ]];
    }

    private function setToken(string $token, string $expireTime) : bool
    {
        if ($this->isTokenExist() === false) {
            return $this->storage->set($this->nameToken, $token, strtotime($expireTime)-time());
        }
        return true;
    }

    public function authenticate() : void
    {
        $response = $this->client->__soapCall("GetMessage", $this->getBodyXml());
        $this->xmlResponse = simplexml_load_string($response->Message->any, AuthXmlResponse::class);

        if ($this->xmlResponse->isError()) {
            $this->exceptionGetToken();
        }
        $this->setToken($this->xmlResponse->getToken(), $this->xmlResponse->getTime());
    }

    public function isTokenExist() : bool
    {
        return $this->storage->has($this->nameToken);
    }

    public function getTempToken() : string
    {
        if ($this->isTokenExist()) {
            return $this->storage->get($this->nameToken);
        }
        return '';
    }
}
