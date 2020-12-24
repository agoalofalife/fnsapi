<?php
declare(strict_types=1);

namespace Fns;

use Fns\Contracts\ResponseSendMessage;
use Fns\GetMessage\Request\GetMessageRequest;

class SendMessageRequest
{
    private $ticket;

    /**
     * @var ClientSoap
     */
    private $client;
    /**
     * @var GetMessageRequest
     */
    private $messageRequest;
    private $server;

    public function __construct(
        ClientSoap $clientSoap,
        GetMessageRequest $messageRequest
    ) {
        $this->client = $clientSoap->getClient();

        $this->messageRequest = $messageRequest;
        $this->messageRequest->setClient($clientSoap);
    }

    protected function getXml() : array
    {
        $type = $this->messageRequest->getTypeMessage();

        return [[
            'Message' => [
                'any' => "<{$type}Request xmlns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0\"
                    xmlns:tns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0\">
                        <tns:{$type}Info>
                                {$this->ticket->asXml()}
                        </tns:{$type}Info>
                    </{$type}Request>"
            ]
        ]];
    }

    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function execute() : ResponseSendMessage
    {
        $messageId = $this->client->__soapCall('SendMessage', $this->getXml())->MessageId;

        $this->messageRequest->setMessageId($messageId);
        $this->messageRequest->send();
        return $this->messageRequest->getResponse();
    }
}
