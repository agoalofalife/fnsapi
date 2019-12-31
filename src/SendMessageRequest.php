<?php
declare(strict_types=1);

namespace Fns;

class SendMessageRequest extends AbstractRequest
{
    private $ticket;
    private $typeRequest;

    protected function getXml() : array
    {
        return [[
            'Message' => [
                'any' =>
                    "<{$this->typeRequest}Request 
                    xmlns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0\"
                    xmlns:tns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0\">
                        <tns:{$this->typeRequest}Info>
                                {$this->ticket->asXml()}
                        </tns:{$this->typeRequest}Info>
                    </{$this->typeRequest}Request>"
            ]
        ]];
    }

    private function setTypeRequest(string $type)
    {
        $this->typeRequest = $type;
    }

    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function checkTicket() : string
    {
        $this->setTypeRequest('CheckTicket');
        return $this->client->__soapCall("SendMessage", $this->getXml())->MessageId;
    }

    public function getTicket() : string
    {
        $this->setTypeRequest('GetTicket');
        return $this->client->__soapCall("SendMessage", $this->getXml())->MessageId;
    }
}
