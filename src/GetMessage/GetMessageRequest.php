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

    /**
     * Check response status is Completed
     * @param $response
     * @return bool
     */
    protected function isCompleted($response) : bool
    {
        return $response->ProcessingStatus === self::COMPLETED;
    }

    /**
     * Return array xml with parameter MessageId
     * @return array
     */
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

    /**
     * Get message type:CheckTicket or GetTicket
     * @return string
     */
    abstract public function getTypeMessage() : string;

    /**
     * Get class child SimpleXMLElement for handler response
     * @return string
     */
    abstract public function getXmlResponseClass() : string;

    abstract public function setXmlResponseClass(string $name);

    abstract public function getResponse() : ResponseSendMessage;

}
