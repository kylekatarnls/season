<?php

namespace Season;

use Carbon\CarbonImmutable;
use Tests\Season\SeasonTest;

class SeasonCarbonImmutableTest extends SeasonTest
{
    const DATE_CLASS = CarbonImmutable::class;
}
