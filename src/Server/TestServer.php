<?php
declare(strict_types=1);

namespace Fns\Server;

use Fns\Contracts\Server;
use Fns\GetMessage\GetTicketXmlResponseTestServer;

class TestServer implements Server
{

    /**
     * Get main wsdl address
     * @return string
     */
    public function getWsdl(): string
    {
        return 'https://openapi.nalog.ru:8090/open-api/ais3/KktTestService/0.1?wsdl';
    }

    /**
     * Get namespace for post receipt info
     * @return string
     */
    public function getNamespaces(): string
    {
        return 'xmlns="urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0"
                    xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0"';
    }

    /**
     * Get handler for handle response info about the ticket
     * @return string
     */
    public function getTicketXmlResponse(): string
    {
        return GetTicketXmlResponseTestServer::class;
    }
}
