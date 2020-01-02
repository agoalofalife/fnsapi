<?php
declare(strict_types=1);

namespace Fns\GetMessage\TimeoutStrategies;

use Fns\Contracts\RequestsManager;
use Fns\Contracts\TimeoutStrategyHandler;

class ExponentialBackoff implements TimeoutStrategyHandler
{
    /**
     * @var RequestsManager
     */
    private $manager;

    private $expMinDelayMicSeconds = 400;
    private $expMaxDelayMicSeconds = 60000;
    private $expFactor = 2.71828;
    private $expJitter = 0.1;

    public function __construct(RequestsManager $manager)
    {
        $this->manager = $manager;
    }

    public function handleTimeout()
    {
        $delay = $this->expMinDelayMicSeconds;

        while (true) {
            $this->manager->executeRequest();
            if ($this->manager->isProcessFinished()) {
                break;
            }
            dump((int)$delay);die();
            usleep((int)$delay);
            $delay = $delay * $this->expFactor;
            if ($delay > $this->expMaxDelayMicSeconds) {
                throw new Exception('Timeout on server side expired');
//                $delay = $expMaxDelay;
            }
            $delay += cumnormdist($delay * $this->expJitter);
        }
    }
}
