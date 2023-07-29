<?php

declare(strict_types=1);

namespace Carbon;

class Carbon
{
    public static function setSeasonConfig(\ArrayAccess|array|null $config): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:13
    }

    public static function getSeason(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:21
    }

    public static function isInSpring(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:29
    }

    public static function isInSummer(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:37
    }

    public static function isInFall(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:45
    }

    public static function isInWinter(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:53
    }

    public static function startOfSeason(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:58
    }

    public static function endOfSeason(\ArrayAccess|array|null $config = null): static
    {
        // Content: see /src/Cmixin/SeasonMixin.php:63
    }
}
