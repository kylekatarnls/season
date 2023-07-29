<?php

declare(strict_types=1);

namespace Carbon
{
    class Carbon
    {
        public function setSeasonConfig(\ArrayAccess|array|null $config): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:13
        }

        public function getSeason(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:21
        }

        public function isInSpring(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:29
        }

        public function isInSummer(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:37
        }

        public function isInFall(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:45
        }

        public function isInWinter(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:53
        }

        public function startOfSeason(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:58
        }

        public function endOfSeason(\ArrayAccess|array|null $config = null): static
        {
            // Content: see /src/Cmixin/SeasonMixin.php:63
        }
    }
}
