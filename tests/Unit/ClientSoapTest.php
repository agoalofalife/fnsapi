<?php
declare(strict_types=1);

namespace Tests\Unit;

use Fns\ClientSoap;
use Psr\SimpleCache\CacheInterface;
use Tests\TestCase;

class ClientSoapTest extends TestCase
{
    public function testCorrectHeaderString()
    {
        $tempToken = $this->randomString(32);
        $userId = $this->randomString(10);

        $regexToken = "FNS-OpenApi-Token:{$tempToken}";
        $regexUserToken = "FNS-OpenApi-UserToken:{$userId}";
        $mockStorage = $this->mock(CacheInterface::class);
        $mockStorage->shouldReceive('get')->with('temp_token')->once()->andReturn($tempToken);

        $client = new ClientSoap($userId, $mockStorage);
        $this->assertInstanceOf(\SoapClient::class, $client->getClient());
        $header = stream_context_get_options($client->getClient()->_stream_context)['http']['header'];
        $this->assertRegExp("/{$regexToken}/", $header, 'ClientSoap is error in method:getHeaderString');
        $this->assertRegExp("/{$regexUserToken}/", $header, 'ClientSoap is error in method:getHeaderString');
    }
}
