<?php
use Fns\GetMessage\TimeoutStrategies\ExponentialBackoff;
use Fns\SendMessageRequest;

// PSR-16
$cache = new Sarahman\SimpleCache\FileSystemCache(__DIR__);
$master_token = '';
$auth = new Fns\Auth\AuthRequest($master_token, $cache);
$auth->authenticate();

// prepare check
$ticket = new \Fns\Ticket();
$ticket->setDate('');
$ticket->setFiscalDocumentId(11111);
$ticket->setFiscalSign(111111111);
$ticket->setFn(1111111111111111);
$ticket->setSum(11111);
$ticket->setTypeOperation(1);

// build new soap client
$client = new \Fns\ClientSoap('unique', $cache);


$message = new \Fns\GetMessage\GetTicketRequest();
$message->setTimeoutStrategy(new ExponentialBackoff($message));
 // или добавить максимальное значение
$message->setTimeoutStrategy(new ExponentialBackoff($message, 600000000));

$request = new SendMessageRequest($client, $message);
$request->setTicket($ticket);
$response = $request->execute();

if ($response->getCode() === 200) {
    dump(json_decode($response->getBody()));
}

