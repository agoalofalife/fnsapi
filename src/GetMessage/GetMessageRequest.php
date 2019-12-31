<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Fns\AbstractRequest;

class GetMessageRequest extends AbstractRequest
{
    private $messageId;
    protected $xmlResponse;

    const PROCESSING = 'PROCESSING';
    const COMPLETED = 'COMPLETED';

    protected function isCompleted($response) : bool
    {
        return $response->ProcessingStatus === self::COMPLETED;
    }

    protected function getXml() : array
    {
        return [['MessageId' =>  $this->messageId]];
    }

    /**
     * @param string $messageId
     */
    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }
}
