<?php

namespace Jeffersongoncalves\Partnerstack\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\Partnerstack\Partnerstack
 */
class Partnerstack extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-partnerstack';
    }
}
