<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Fns\Contracts\RequestsManager;
use Fns\Contracts\ResponseSendMessage;
use Fns\Contracts\SetTimeoutHandler;
use Fns\Contracts\TimeoutStrategyHandler;

class GetTicketRequest extends GetMessageRequest implements RequestsManager, SetTimeoutHandler
{
    private $handlerXmlResponse = GetTicketXmlResponse::class;
    private $response;

    /**
     * @var ResponseSendMessage
     */
    private $xmlResponse;
    private $strategyTimeout;

    public function setTimeoutStrategy(TimeoutStrategyHandler $strategyHandler)
    {
        $this->strategyTimeout = $strategyHandler;
    }

    public function send()
    {
        $this->strategyTimeout->handleTimeout();
        $this->xmlResponse = simplexml_load_string($this->response->Message->any, $this->getXmlResponseClass());
    }

    public function getTypeMessage(): string
    {
        return 'GetTicket';
    }

    public function getXmlResponseClass(): string
    {
        return $this->handlerXmlResponse;
    }

    public function setXmlResponseClass(string $name)
    {
        $this->handlerXmlResponse = $name;
    }

    public function getResponse(): ResponseSendMessage
    {
        return $this->xmlResponse;
    }

    public function isProcessFinished(): bool
    {
        return $this->isCompleted($this->response);
    }

    public function executeRequest(): void
    {
        $this->response = $this->client->__soapCall("GetMessage", $this->getXml());
    }
}
