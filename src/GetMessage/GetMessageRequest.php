<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Fns\ClientSoap;
use Fns\Contracts\ResponseSendMessage;

abstract class GetMessageRequest
{
    private $messageId;
    protected $client;

    const PROCESSING = 'PROCESSING';
    const COMPLETED = 'COMPLETED';

    protected function isCompleted($response) : bool
    {
        return $response->ProcessingStatus === self::COMPLETED;
    }

    protected function getXml() : array
    {
        return [['MessageId' =>  $this->messageId]];
    }

    /**
     * @param string $messageId
     */
    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    public function setClient(ClientSoap $clientSoap)
    {
        $this->client = $clientSoap->getClient();
    }

    abstract public function send();

    abstract public function getTypeMessage() : string;

    abstract public function getXmlResponseClass() : string;

    abstract public function getResponse() : ResponseSendMessage;

}
