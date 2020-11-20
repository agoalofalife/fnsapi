<?php
declare(strict_types=1);

namespace Fns\Server;

class FactoryServer
{
    public function createServer(int $version = 1)
    {
        if ($version === 1) {
            return new BaseServer();
        } elseif ($version === 2) {
            return new TestServer();
        } else {
            throw new \Exception('Type server was not found');
        }
    }
}
