<?php

namespace JeffersonGoncalves\PartnerStack;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PartnerStackServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('partnerstack')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(PartnerStack::class);
    }
}
