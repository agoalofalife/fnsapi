<?php
declare(strict_types=1);

namespace Fns\GetMessage\Request;

use Fns\Contracts\RequestsManager;
use Fns\Contracts\ResponseSendMessage;
use Fns\Contracts\SetTimeoutHandler;
use Fns\Contracts\TimeoutStrategyHandler;
use Fns\GetMessage\Response\CheckTicketXmlResponse;

class CheckTicketRequest extends GetMessageRequest implements RequestsManager, SetTimeoutHandler
{
    private $response;
    /**
     * @var ResponseSendMessage
     */
    private $xmlResponse;

    private $handlerXmlResponse = CheckTicketXmlResponse::class;

    /**
     * @var TimeoutStrategyHandler
     */
    private $strategyTimeout;

    public function isProcessFinished() : bool
    {
        return $this->isCompleted($this->response);
    }

    public function executeRequest() : void
    {
        $this->response = $this->client->__soapCall('GetMessage', $this->getXml());
    }

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
        return 'CheckTicket';
    }

    public function getXmlResponseClass(): string
    {
        return CheckTicketXmlResponse::class;
    }

    public function getResponse(): ResponseSendMessage
    {
        return $this->xmlResponse;
    }
}
