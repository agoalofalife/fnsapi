<?php
declare(strict_types=1);

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
    /**
     * @var Ticket
     */
    private $ticket;

    public function __construct(string $userToken, CacheInterface $cache, Ticket $ticket)
    {
        $this->userToken = $userToken;
        $this->storage = $cache;
        $this->client = new SoapClient(
            $this->wsdl,
            [
                'stream_context' => stream_context_create(['http' => ['header' => $this->getHeaderString()]])
            ]
        );
        $this->ticket = $ticket;
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

    private function xmlCheckTicket() : array
    {
        return [[
            'Message' => [
                'any' =>
                    "<CheckTicketRequest 
                    xmlns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0\"
                    xmlns:tns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0\">
                        <tns:CheckTicketInfo>
                                {$this->ticket->asXml()}
                        </tns:CheckTicketInfo>
                    </CheckTicketRequest>"
            ]
        ]];
    }

    public function checkTicketRequest() : string
    {
        return $this->client->__soapCall("SendMessage", $this->xmlCheckTicket())->MessageId;
    }
}
