<?php


namespace Fns;


use Psr\SimpleCache\CacheInterface;
use SoapClient;

class SendMessageRequest
{
    private $wsdl = 'https://openapi.nalog.ru:8090/open-api/ais3/KktService/0.1?wsdl';

    /**
     * @var string
     */
    private $userToken;
    /**
     * @var CacheInterface
     */
    private $storage;
    /**
     * @var SoapClient
     */
    private $client;

    public function __construct(string $userToken, CacheInterface $cache)
    {
        $this->userToken = $userToken;
        $this->client = new SoapClient(
            $this->wsdl,
            [
                'stream_context' => stream_context_create(['http' => ['header' => $this->getHeaderString()]])
            ]
        );
        $this->storage = $cache;
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
}
