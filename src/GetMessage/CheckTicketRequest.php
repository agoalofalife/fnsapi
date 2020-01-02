<?php
declare(strict_types=1);

namespace Fns\GetMessage;

use Exception;
use Fns\Contracts\RequestsManager;
use Fns\Contracts\TimeoutStrategyHandler;
use Psr\SimpleCache\CacheInterface;

class CheckTicketRequest extends GetMessageRequest implements RequestsManager
{
    private $response;
    /**
     * @var TimeoutStrategyHandler
     */
    private $strategyTimeout;
    /**
     * @var string
     */
    private $messageId;

    public function __construct(string $userToken, CacheInterface $cache, string $messageId)
    {
        parent::__construct($userToken, $cache);
        $this->messageId = $messageId;
    }

    public function isProcessFinished() : bool
    {
        return $this->isCompleted($this->response);
    }

    public function executeRequest() : void
    {
        $this->response = $this->client->__soapCall("GetMessage", $this->getXml());
    }

    public function setTimeoutStrategy(TimeoutStrategyHandler $strategyHandler)
    {
        $this->strategyTimeout = $strategyHandler;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function checkExist() : bool
    {
        $this->strategyTimeout->handleTimeout();
        return $this->xmlResponse->isCheckExist();
    }
}
