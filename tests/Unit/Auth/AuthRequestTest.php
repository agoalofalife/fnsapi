<?php
declare(strict_types=1);

namespace Tests\Unit\Auth;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\SimpleCache\CacheInterface;
use SoapClient;
use Tests\TestCase;
use UnexpectedValueException;

class AuthRequestTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    private $storage;
    /**
     * @var MockObject
     */
    private $soapClientMock;
    private $tempToken;

    /**
     *
     */
    public function setUp()
    {
        $this->storage = $this->mock(CacheInterface::class);
        $this->soapClientMock = $this->getMockBuilder(SoapClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tempToken = $this->getToken();
    }

    public function testAuthHappyFlow()
    {
        $this->soapClientMock->expects($this->any())
            ->method('__soapCall')
            ->willReturn($this->fakeAuthResponse($this->tempToken));

        $this->storage->shouldReceive('has')->with('temp_token')->once()->andReturnFalse();
        $this->storage->shouldReceive('set')
            ->with('temp_token', $this->tempToken, \Mockery::any())
            ->once()
            ->andReturnTrue();
        $auth = new \Fns\Auth\AuthRequest($this->getToken(), $this->storage);
        $auth->setSoapClient($this->soapClientMock);
        $auth->authenticate();
    }

    public function testAuthIsExistToken()
    {
        $this->soapClientMock->expects($this->any())
            ->method('__soapCall')
            ->willReturn($this->fakeAuthResponse($this->tempToken));

        $this->storage->shouldReceive('has')->with('temp_token')->once()->andReturnTrue();
        $this->storage->shouldNotReceive('set');
        $auth = new \Fns\Auth\AuthRequest($this->getToken(), $this->storage);
        $auth->setSoapClient($this->soapClientMock);
        $auth->authenticate();
    }

    public function testAuthExceptionToken()
    {
        $this->soapClientMock->expects($this->any())
            ->method('__soapCall')
            ->willReturn($this->fakeAuthResponse($this->tempToken, '', true));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Temp token has not got');

        $auth = new \Fns\Auth\AuthRequest($this->getToken(), $this->storage);
        $auth->setSoapClient($this->soapClientMock);
        $auth->authenticate();
    }
    private function getToken() : string
    {
        return $this->randomString(20);
    }

    private function fakeAuthResponse(
        string $token,
        $date = '2020-01-04T20:16:05 +03:00',
        bool $isError = false
    ) : object {
        $bodyDependOnErrorParam = "<tns:Result><tns:Token>{$token}</tns:Token>
                    <tns:ExpireTime>{$date}</tns:ExpireTime>
                    </tns:Result>";
        if ($isError) {
            $bodyDependOnErrorParam = "<tns:Fault><tns:Message>Error</tns:Message></tns:Fault>";
        }


        return (object)['Message' => (object)[
            'any' => "<tns:AuthResponse xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                    xmlns:ns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageProviderService/types/1.0\" 
                    xmlns:tns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0\">
                    {$bodyDependOnErrorParam}
                    </tns:AuthResponse>"
        ]];
    }
}
