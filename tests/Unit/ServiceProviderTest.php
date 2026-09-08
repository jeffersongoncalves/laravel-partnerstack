<?php

use JeffersonGoncalves\PartnerStack\Facades\PartnerStack as PartnerStackFacade;
use JeffersonGoncalves\PartnerStack\PartnerStack;

it('registers the PartnerStack singleton', function () {
    expect(app(PartnerStack::class))->toBeInstanceOf(PartnerStack::class);
});

it('resolves the facade to the PartnerStack class', function () {
    expect(PartnerStackFacade::getFacadeRoot())->toBeInstanceOf(PartnerStack::class);
});

it('merges the partnerstack config file', function () {
    expect(config('partnerstack.base_url'))->toBe('https://api.partnerstack.com/api/v2');
});
