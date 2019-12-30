<?php
declare(strict_types=1);

namespace Fns;

use Psr\SimpleCache\CacheInterface;
use SoapClient;

class GetMessageRequest
{
    private $messageId;
    private $client;
    private $wsdl = 'https://openapi.nalog.ru:8090/open-api/ais3/KktService/0.1?wsdl';
    /**
     * @var CacheInterface
     */
    private $storage;
    /**
     * @var string
     */
    private $userToken;

    public function __construct(string $userToken, $messageId, CacheInterface $cache)
    {
        $this->messageId = $messageId;
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

    private function getXml() : array
    {
        return [
            [
                'MessageId' =>  $this->messageId
            ]
        ];
    }
    public function getInfo()
    {
        $result = $this->client->__soapCall("GetMessage", $this->getXml());
        dump($result);
    }
}