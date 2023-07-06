<?php

namespace Tests\Cmixin;

use Carbon\CarbonImmutable;

/**
 * @group immutable
 */
class SeasonMixinImmutableTest extends SeasonMixinTest
{
    const CARBON_CLASS = CarbonImmutable::class;
}
