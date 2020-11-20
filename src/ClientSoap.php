<?php
declare(strict_types=1);

namespace Fns;

use Psr\SimpleCache\CacheInterface;
use SoapClient;

class ClientSoap
{
    private $storage;
    private $userToken;
    private $client;
    private $wsdl;

    public function __construct(string $userToken, CacheInterface $cache)
    {
        $this->storage = $cache;
        $this->userToken = $userToken;
    }

    private function getHeaderString() : string
    {
        return sprintf(
            "FNS-OpenApi-Token:%s
                    FNS-OpenApi-UserToken:%s",
            $this->storage->get('temp_token'),
            $this->userToken
        );
    }

    public function getClient() : SoapClient
    {
        return $this->client = new SoapClient(
            $this->wsdl,
            [
                "trace"          => 1,
                'stream_context' => stream_context_create(['http' => ['header' => $this->getHeaderString()]])
            ]
        );
    }

    public function setWsdl(string $wsdl)
    {
        $this->wsdl = $wsdl;
    }
}
