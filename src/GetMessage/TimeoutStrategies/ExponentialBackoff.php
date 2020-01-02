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

    private $expMinDelayMicroSeconds = 500000;
    private $expMaxDelayMicroSeconds = 60000000;
    private $expFactor = 2.71828;
    private $expJitter = 0.1;

    public function __construct(RequestsManager $manager)
    {
        $this->manager = $manager;
    }

    public function handleTimeout()
    {
        $delay = $this->expMinDelayMicroSeconds;

        while (true) {
            $this->manager->executeRequest();
            if ($this->manager->isProcessFinished()) {
                break;
            }
            echo $delay . PHP_EOL;
            usleep((int)$delay);
            $delay = $delay * $this->expFactor;
            if ((int)$delay > $this->expMaxDelayMicroSeconds) {
                throw new Exception('Timeout on server side expired');
//                $delay = $expMaxDelay;
            }
            $delay += cumnormdist($delay * $this->expJitter);
        }
    }
}
