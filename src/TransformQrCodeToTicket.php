<?php

namespace Fns;

use Carbon\Carbon;

class TransformQrCodeToTicket
{
    private $ticket;
    private $qrcodeString;

    private $regexToTicketMethod = [
        '/(i\=)([0-9]+)/'          => [
            'method' => 'setFiscalDocumentId',
            'type'   => 'getInteger'
        ],
        '/(fp\=)([0-9]+)/'         => [
            'method' => 'setFiscalSign',
            'type'   => 'getInteger'
        ],
        '/(fn\=)([0-9]+)/'         => [
            'method' => 'setFn',
            'type'   => 'getInteger'
        ],
        '/(s\=)(([0-9]+\.?[0-9]+)|[0-9]+)/' => [
            'method' => 'setSum',
            'filter' => 'aroundSum',
            'type'   => 'getInteger'
        ],
        '/(t\=)([0-9]+\T[0-9]+)/'  => [
            'method' => 'setDate',
            'filter' => 'toDateTimeLocalString',
            'type'   => 'getString'
        ],
        '/(n\=)([0-9]+)$/'         => [
            'method' => 'setTypeOperation',
            'type'   => 'getInteger'
        ],

    ];

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function setQrCode(string $qrcode)
    {
        $this->qrcodeString = $qrcode;
    }

    public function getTicket(): Ticket
    {
        foreach ($this->regexToTicketMethod as $regex => $config) {
            $temp = [];

            preg_match($regex, $this->qrcodeString, $temp);
            if (isset($config['filter'])) {
                $value = $this->{$config['filter']}(array_pop($temp));
            } else {
                $value = array_pop($temp);
            }
            $normalizeType = $this->{$config['type']}($value);
            $this->ticket->{$config['method']}($normalizeType);
        }
        return $this->ticket;
    }

    private function aroundSum(string $sum): int
    {
        return round(($sum * 100), 0);
    }

    private function toDateTimeLocalString(string $date): string
    {
        return Carbon::parse($date)->toDateTimeLocalString();
    }

    private function getInteger($value): int
    {
        return (int) $value;
    }

    private function getString($value): string
    {
        return (string) $value;
    }
}
