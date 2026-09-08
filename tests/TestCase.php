<?php

namespace Jeffersongoncalves\Partnerstack\Tests;

use Jeffersongoncalves\Partnerstack\PartnerstackServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PartnerstackServiceProvider::class,
        ];
    }
}
