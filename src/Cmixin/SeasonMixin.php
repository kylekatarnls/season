<?php

namespace Cmixin;

use ArrayAccess;
use Season\Season;
use Season\SeasonEnum;

trait SeasonMixin
{
    private static ArrayAccess|array|null $defaultSeasonConfig = null;

    public function setSeasonConfig(ArrayAccess|array|null $config): void
    {
        static::$defaultSeasonConfig = $config;
    }

    /**
     * Return the season of the current date.
     */
    public function getSeason(ArrayAccess|array|null $config = null): SeasonEnum
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->getSeason($this);
    }

    /**
     * Return either current date is in spring.
     */
    public function isInSpring(ArrayAccess|array|null $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInSpring($this);
    }

    /**
     * Return either current date is in summer.
     */
    public function isInSummer(ArrayAccess|array|null $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInSummer($this);
    }

    /**
     * Return either current date is in fall.
     */
    public function isInFall(ArrayAccess|array|null $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInFall($this);
    }

    /**
     * Return either current date is in winter.
     */
    public function isInWinter(ArrayAccess|array|null $config = null): bool
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->isInWinter($this);
    }

    public function startOfSeason(ArrayAccess|array|null $config = null): static
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->startOfSeason($this);
    }

    public function endOfSeason(ArrayAccess|array|null $config = null): static
    {
        return (new Season($config ?? static::$defaultSeasonConfig))->endOfSeason($this);
    }
}
