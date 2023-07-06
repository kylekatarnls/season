<?php

namespace Tests\Cmixin;

use Carbon\Carbon;
use Cmixin\SeasonMixin;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Season\Season;

/**
 * @group mutable
 */
class SeasonMixinTest extends TestCase
{
    const CARBON_CLASS = Carbon::class;

    protected function setUp(): void
    {
        $carbon = static::CARBON_CLASS;
        $carbon::mixin(SeasonMixin::class);
    }

    public function testStartOfSeason(): void
    {
        $carbon = static::CARBON_CLASS;
        $date = $carbon::parse('2022-12-28');
        $method = $date instanceof DateTimeImmutable ? 'assertNotSame' : 'assertSame';
        $start = $date->startOfSeason();
        $this->$method($date, $start);
        $date = $carbon::parse('2022-12-28');
        $this->assertSame(
            '2022-12-21 00:00:00.000000',
            $date->copy()->startOfSeason()->format('Y-m-d H:i:s.u'),
        );
        $date = $carbon::parse('2022-01-01 23:54');
        $this->assertSame(
            '2021-12-21 00:00:00.000000',
            $date->copy()->startOfSeason()->format('Y-m-d H:i:s.u'),
        );
        $date = $carbon::parse('2022-01-01 23:54');
        $this->assertSame(
            '2021-12-21 00:00:00.000000',
            $date->copy()->startOfSeason()->startOfSeason()->format('Y-m-d H:i:s.u'),
        );
    }

    public function testEndOfSeason(): void
    {
        $carbon = static::CARBON_CLASS;
        $date = $carbon::parse('2022-12-28');
        $method = $date instanceof DateTimeImmutable ? 'assertNotSame' : 'assertSame';
        $this->$method(
            $date,
            $date->endOfSeason(),
        );
        $date = $carbon::parse('2022-12-28');
        $this->assertSame(
            '2023-03-19 23:59:59.999999',
            $date->copy()->endOfSeason()->format('Y-m-d H:i:s.u'),
        );
        $date = $carbon::parse('2022-01-01 23:54');
        $this->assertSame(
            '2022-03-19 23:59:59.999999',
            $date->copy()->endOfSeason()->format('Y-m-d H:i:s.u'),
        );
        $date = $carbon::parse('2022-01-01 23:54');
        $this->assertSame(
            '2022-03-19 23:59:59.999999',
            $date->copy()->endOfSeason()->endOfSeason()->format('Y-m-d H:i:s.u'),
        );
    }

    public function testGetSeason(): void
    {
        $carbon = static::CARBON_CLASS;
        $date = $carbon::parse('2022-12-28');

        $this->assertSame(
            Season::WINTER,
            $date->getSeason(),
        );
    }

    public function testIsInWinter(): void
    {
        $carbon = static::CARBON_CLASS;

        $this->assertTrue($carbon::parse('2022-12-21')->isInWinter());
        $this->assertTrue($carbon::parse('2022-12-28')->isInWinter());
        $this->assertTrue($carbon::parse('2022-01-03')->isInWinter());
        $this->assertFalse($carbon::parse('2022-12-20')->isInWinter());
    }

    public function testIsInSpring(): void
    {
        $carbon = static::CARBON_CLASS;

        $this->assertTrue($carbon::parse('2022-03-20')->isInSpring());
        $this->assertTrue($carbon::parse('2022-03-28')->isInSpring());
        $this->assertFalse($carbon::parse('2022-02-25')->isInSpring());
        $this->assertFalse($carbon::parse('2022-03-19')->isInSpring());
    }

    public function testIsInSummer(): void
    {
        $carbon = static::CARBON_CLASS;

        $this->assertTrue($carbon::parse('2022-06-21')->isInSummer());
        $this->assertTrue($carbon::parse('2022-06-28')->isInSummer());
        $this->assertFalse($carbon::parse('2022-05-25')->isInSummer());
        $this->assertFalse($carbon::parse('2022-06-20')->isInSummer());
    }

    public function testIsInFall(): void
    {
        $carbon = static::CARBON_CLASS;

        $this->assertTrue($carbon::parse('2022-09-22')->isInFall());
        $this->assertTrue($carbon::parse('2022-09-28')->isInFall());
        $this->assertFalse($carbon::parse('2022-08-25')->isInFall());
        $this->assertFalse($carbon::parse('2022-09-21')->isInFall());
    }
}
