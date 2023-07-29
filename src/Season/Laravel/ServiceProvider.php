<?php

namespace Season\Laravel;

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

        if (!($seasonConfig['mixin'] ?? true)) {
            return;
        }

        foreach ($this->getCarbonClasses() as $carbonClass) {
            $carbonClass::mixin(SeasonMixin::class);

            if ($seasonConfig !== []) {
                $carbonClass::setSeasonConfig($seasonConfig);
            }
        }
    }

    private function proceedConfig(mixed $config): mixed
    {
        if ($config instanceof Closure) {
            return $config($this->app);
        }

        return $config;
    }

    private function getCarbonClasses(): array
    {
        return array_filter([
            Carbon::class,
            CarbonImmutable::class,
            Illuminate\Support\Carbon::class,
            Date::class,
        ], 'class_exists');
    }

    private function getConfig(): array
    {
        $config = $this->app->get('config');
        $carbonConfig = $this->proceedConfig($config->get('carbon'));

        return is_array($carbonConfig)
            ? $this->proceedConfig(
                $carbonConfig['season'] ??
                $carbonConfig['seasons'] ??
                $this->proceedConfig($config->get('seasons')) ??
                [],
            )
            : [];
    }
}
