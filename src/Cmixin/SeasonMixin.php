<?php

namespace Cmixin;

use Season\Season;
use Season\SeasonEnum;

trait SeasonMixin
{
    private static ?array $defaultSeasonConfig = null;

    public function setSeasonConfig(?array $config): void
    {
        static::$defaultSeasonConfig = $config;
    }

    /**
     * Return the season of the current date.
     */
    public function getSeason(?array $config = null): SeasonEnum
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->getSeason($this);
    }

    /**
     * Return either current date is in spring.
     */
    public function isInSpring(?array $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInSpring($this);
    }

    /**
     * Return either current date is in summer.
     */
    public function isInSummer(?array $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInSummer($this);
    }

    /**
     * Return either current date is in fall.
     */
    public function isInFall(?array $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInFall($this);
    }

    /**
     * Return either current date is in winter.
     */
    public function isInWinter(?array $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInWinter($this);
    }

    public function startOfSeason(?array $config = null): static
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->startOfSeason($this);
    }

    public function endOfSeason(?array $config = null): static
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->endOfSeason($this);
    }
}
