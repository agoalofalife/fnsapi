<?php
declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Mockery;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $class
     *
     * @return \Mockery\Mock|mixed
     */
    protected function mock(string $class)
    {
        return Mockery::mock($class);
    }
    protected function faker()
    {
        return Factory::create();
    }

    protected function randomString($len)
    {
        $string = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for ($i=0; $i < $len; $i++) {
            $string .= substr($chars, rand(0, strlen($chars)), 1);
        }
        return $string;
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
