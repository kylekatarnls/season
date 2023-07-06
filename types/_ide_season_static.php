<?php

declare(strict_types=1);

namespace Carbon
{
    class Carbon
    {
        public static function setSeasonConfig(array $config): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:12
        }

        public static function getSeason(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:20
        }

        public static function isInSpring(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:28
        }

        public static function isInSummer(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:36
        }

        public static function isInFall(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:44
        }

        public static function isInWinter(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:52
        }

        public static function startOfSeason(?array $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:57
        }

        public static function endOfSeason(?array $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:62
        }
    }
}
