<?php
declare(strict_types=1);

namespace Tests\Unit;

use Fns\Contracts\OutXml;
use Fns\Ticket;
use Tests\TestCase;

class TicketTest extends TestCase
{
    public function testTicketImplementsOutXml()
    {
        $this->assertInstanceOf(OutXml::class, new Ticket());
    }

    public function testXmlView()
    {
        $unixTimeRandom = mt_rand(1262055681, 1262055681);
        $dateRandom = date("Y-m-d\TH:i:s", $unixTimeRandom);
        $fiscalDocument = rand(10000, 99999);
        $fiscalSign = rand(100000000, 999999999);
        $fn = rand(1000000000000000, 9999999999999999);
        $sum = rand(10000, 99999);

        $ticket = new Ticket();
        $ticket->setDate($dateRandom);
        $ticket->setFiscalDocumentId($fiscalDocument);
        $ticket->setFiscalSign($fiscalSign);
        $ticket->setFn($fn);
        $ticket->setSum($sum);
        $ticket->setTypeOperation(1);

        $this->assertRegExp(
            "/\<tns\:Date\>{$dateRandom}\<\/tns\:Date\>/",
            $ticket->asXml(),
            'In xml Ticket parameter data is incorrect'
        );
        $this->assertRegExp(
            "/\<tns\:FiscalDocumentId\>{$fiscalDocument}\<\/tns\:FiscalDocumentId\>/",
            $ticket->asXml(),
            'In xml Ticket parameter FiscalDocumentId is incorrect'
        );
        $this->assertRegExp(
            "/\<tns\:FiscalSign\>{$fiscalSign}\<\/tns\:FiscalSign\>/",
            $ticket->asXml(),
            'In xml Ticket parameter fiscalSign is incorrect'
        );

        $this->assertRegExp(
            "/\<tns\:Fn\>{$fn}\<\/tns\:Fn\>/",
            $ticket->asXml(),
            'In xml Ticket parameter fn is incorrect'
        );
        $this->assertRegExp(
            "/\<tns\:Sum\>{$sum}\<\/tns\:Sum\>/",
            $ticket->asXml(),
            'In xml Ticket parameter Sum is incorrect'
        );
    }
}
