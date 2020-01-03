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
    private $wsdl = 'https://openapi.nalog.ru:8090/open-api/ais3/KktService/0.1?wsdl';

    public function __construct(string $userToken, CacheInterface $cache)
    {
        $this->storage = $cache;
        $this->userToken = $userToken;
        $this->client = new SoapClient(
            $this->wsdl,
            [
                'stream_context' => stream_context_create(['http' => ['header' => $this->getHeaderString()]])
            ]
        );
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
        return $this->client;
    }
}
