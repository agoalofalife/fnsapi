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

    public function __construct(RequestsManager $manager)
    {
        $this->manager = $manager;
    }

    public function handleTimeout()
    {
        $expMinDelay = 100;
        $expMaxDelay = 60000;
        $expFactor = 2.71828;
        $expJitter =  0.1;
        $delay = $expMinDelay;

        while (true) {
            $this->manager->executeRequest();
            if ($this->manager->isProcessFinished()) {
                break;
            }

            usleep((int)$delay);
            $delay = $delay * $expFactor;
            if ($delay > $expMaxDelay) {
                throw new Exception('Timeout on server side expired');
//                $delay = $expMaxDelay;
            }
            $delay += cumnormdist($delay * $expJitter);
        }
    }
}
