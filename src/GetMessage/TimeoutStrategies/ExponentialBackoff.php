<?php
declare(strict_types=1);

namespace Fns\GetMessage\TimeoutStrategies;

use Exception;
use Fns\Contracts\RequestsManager;
use Fns\Contracts\TimeoutStrategyHandler;

class ExponentialBackoff implements TimeoutStrategyHandler
{
    /**
     * @var RequestsManager
     */
    private $manager;

    /**
     * Start integer value in microseconds
     * @var int
     */
    private $expMinDelayMicroSeconds = 500000;

    /**
     * Max integer value in microseconds
     * @var int
     */
    private $expMaxDelayMicroSeconds;

    private $expFactor = 2.71828;
    private $expJitter = 0.1;

    public function __construct(RequestsManager $manager, $maxDelay = 60000000)
    {
        $this->manager = $manager;
        $this->expMaxDelayMicroSeconds = $maxDelay;
    }

    public function handleTimeout()
    {
        $delay = $this->expMinDelayMicroSeconds;

        while (true) {
            $this->manager->executeRequest();
            if ($this->manager->isProcessFinished()) {
                break;
            }

            usleep((int)$delay);
            $delay = $delay * $this->expFactor;
            if ((int)$delay > $this->expMaxDelayMicroSeconds) {
                throw new Exception('Timeout on server side expired');
            }
            $delay += cumnormdist($delay * $this->expJitter);
        }
    }
}
