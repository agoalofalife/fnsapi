<?php
declare(strict_types=1);

namespace Fns\Contracts;

interface Server
{
    /**
     * Get main wsdl address
     * @return string
     */
    public function getWsdl(): string;

    /**
     * Get namespace for post receipt info
     * @return string
     */
    public function getNamespaces(): string;

    /**
     * Get handler for handle response info about the ticket
     * @return string
     */
    public function getTicketXmlResponse(): string;
}