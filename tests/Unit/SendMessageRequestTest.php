<?php
declare(strict_types=1);

namespace Tests\Unit;

use Fns\ClientSoap;
use Fns\GetMessage\Request\GetMessageRequest;
use Fns\SendMessageRequest;
use Fns\Ticket;
use SoapClient;
use Tests\TestCase;

class SendMessageRequestTest extends TestCase
{
    public function testExecute()
    {
        $randomMessageId = $this->randomString(5);
        $mockTicket = $this->mock(Ticket::class);
        $mockTicket->shouldReceive('asXml')->once();

        $mockNativeClientSoap = $this->mock(SoapClient::class);
        $mockClient = $this->mock(ClientSoap::class);

        $mockClient->shouldReceive('getClient')->once()->andReturn($mockNativeClientSoap);
        $mockNativeClientSoap->shouldReceive('__soapCall')
            ->once()
            ->andReturn((object)['MessageId' => $randomMessageId]);

        $mockGetMessageRequest = $this->mock(GetMessageRequest::class);
        $mockGetMessageRequest->shouldReceive('setClient')->with($mockClient);
        $mockGetMessageRequest->shouldReceive('getTypeMessage');
        $mockGetMessageRequest->shouldReceive('setMessageId')->with($randomMessageId);
        $mockGetMessageRequest->shouldReceive('send')->once();
        $mockGetMessageRequest->shouldReceive('getResponse')->once();

        $sender = new SendMessageRequest($mockClient, $mockGetMessageRequest);
        $sender->setTicket($mockTicket);
        $sender->execute();
    }
}
