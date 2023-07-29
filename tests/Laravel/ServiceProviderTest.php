<?php

namespace Tests\Carbon\Laravel;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cmixin\SeasonMixin;
use PHPUnit\Framework\TestCase;
use Season\Laravel\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        (new class() {
            use SeasonMixin;
        })->setSeasonConfig(null);

        parent::tearDown();
    }

    public function testBoot(): void
    {
        include_once __DIR__ . '/DeferrableProvider.php';
        include_once __DIR__ . '/ServiceProvider.php';
        $service = new ServiceProvider();
        $message = null;

        Carbon::macro('isInSpring', null);

        try {
            Carbon::parse('2019-04-08')->isInSpring();
        } catch (\BadMethodCallException $e) {
            $message = $e->getMessage();
        }

        $this->assertSame('Method isInSpring does not exist.', $message);

        $this->assertNull($service->boot());

        $this->assertTrue(Carbon::parse('2022-03-21')->isInSpring());
    }

    public function testConfig(): void
    {
        include_once __DIR__ . '/DeferrableProvider.php';
        include_once __DIR__ . '/ServiceProvider.php';
        $service = new ServiceProvider();
        $classes = [
            Carbon::class,
            CarbonImmutable::class,
        ];

        Carbon::macro('getSeason', null);

        $service->app->setSeasonsConfig([
            3  => 22, // spring
            6  => 21, // summer
            9  => 22, // fall
            12 => 21, // winter
        ]);
        $service->boot();

        foreach ($classes as $class) {
            $this->assertSame('winter', $class::parse('2022-03-21')->getSeason()->getName());
            $this->assertSame(12, $class::parse('2022-03-21')->getSeason()->getStartMonth());
        }
    }
}
