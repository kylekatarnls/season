<?php

namespace Tests\Cmixin;

use Carbon\Carbon;

class MyCarbon extends Carbon
{
}

/**
 * @group custom
 */
class SeasonMixinCustomTest extends SeasonMixinTest
{
    const CARBON_CLASS = MyCarbon::class;
}
