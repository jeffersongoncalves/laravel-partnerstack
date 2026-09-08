<?php

namespace JeffersonGoncalves\PartnerStack\Tests;

use JeffersonGoncalves\PartnerStack\PartnerStackServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PartnerStackServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('partnerstack.public_key', 'fake-public-key');
        $app['config']->set('partnerstack.secret_key', 'fake-secret-key');
        $app['config']->set('partnerstack.base_url', 'https://api.partnerstack.com/api/v2');
    }
}
