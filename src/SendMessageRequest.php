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
        $this->client = new SoapClient(
            $this->wsdl,
            [
                'stream_context' => stream_context_create(['http' => ['header' => $this->getHeaderString()]])
            ]
        );
        $this->storage = $cache;
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
                                <tns:Sum>56300</tns:Sum>
                                <tns:Date>2019-12-20T00:28:39</tns:Date>
                                <tns:Fn>9289000100277510</tns:Fn>
                                <tns:TypeOperation>1</tns:TypeOperation>
                                <tns:FiscalDocumentId>160854</tns:FiscalDocumentId>
                                <tns:FiscalSign>2136268623</tns:FiscalSign>
                        </tns:CheckTicketInfo>
                    </CheckTicketRequest>"
            ]
        ]];
    }

    public function checkTicketRequest()
    {
        $result = $this->client->__soapCall("SendMessage", $this->xmlCheckTicket(), null);
        dd($result);
    }
}
