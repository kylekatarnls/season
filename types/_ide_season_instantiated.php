<?php

declare(strict_types=1);

namespace Carbon
{
    class Carbon
    {
        public function setSeasonConfig(array $config): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:12
        }

        public function getSeason(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:20
        }

        public function isInSpring(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:28
        }

        public function isInSummer(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:36
        }

        public function isInFall(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:44
        }

        public function isInWinter(): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:52
        }

        public function startOfSeason(?array $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:57
        }

        public function endOfSeason(?array $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:62
        }
    }
}
