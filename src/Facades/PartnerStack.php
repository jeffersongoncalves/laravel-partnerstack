<?php

namespace JeffersonGoncalves\PartnerStack\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\PartnerStack\PartnerStack as PartnerStackClient;

/**
 * @see PartnerStackClient
 */
class PartnerStack extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PartnerStackClient::class;
    }
}
