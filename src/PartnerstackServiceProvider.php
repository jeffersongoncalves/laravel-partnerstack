<?php

namespace Jeffersongoncalves\Partnerstack;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PartnerstackServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-partnerstack')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
