<?php

namespace Season\Laravel;

use ArrayAccess;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Closure;
use Cmixin\SeasonMixin;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Date;

/**
 * @property Application $app
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $seasonConfig = $this->getConfig();

        foreach ($this->getCarbonClasses() as $carbonClass) {
            $carbonClass::mixin(SeasonMixin::class);

            if ($seasonConfig !== []) {
                $carbonClass::setSeasonConfig($seasonConfig);
            }
        }
    }

    private function getCarbonClasses(): iterable
    {
        return array_filter([
            Carbon::class,
            CarbonImmutable::class,
            Illuminate\Support\Carbon::class,
            Date::class,
        ], 'class_exists');
    }

    private function getConfig(): ArrayAccess|array
    {
        $config = $this->getSeasonConfig();

        if ($config instanceof Closure) {
            $config = $config($this->app);
        }

        if (is_array($config) || $config instanceof ArrayAccess) {
            return $config;
        }

        return [];
    }

    private function getSeasonConfig(): mixed
    {
        $appConfig = $this->app->get('config');

        if ((is_array($appConfig) || $appConfig instanceof ArrayAccess) && isset($appConfig['season'])) {
            return $appConfig['season'];
        }

        if (is_object($appConfig) && method_exists($appConfig, 'has') && $appConfig->has('season')) {
            return $appConfig->get('season');
        }

        return null;
    }
}
