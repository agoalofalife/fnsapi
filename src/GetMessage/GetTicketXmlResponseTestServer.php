<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Fns\Contracts\ResponseSendMessage;
use SimpleXMLElement;

class GetTicketXmlResponseTestServer extends SimpleXMLElement implements ResponseSendMessage
{
    public function isError(): bool
    {
        return $this->getCode() !== 200;
    }

    public function getCode(): int
    {
        $json = json_encode($this);
        return (int) json_decode($json, true)['Result']['Code'];
    }

    public function getBody(): string
    {
        $json = json_encode($this);
        return json_decode($json, true)['Result']['Ticket'];
    }
}
