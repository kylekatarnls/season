<?php

declare(strict_types=1);

namespace Season;

use ReflectionEnum;
use ValueError;

enum SeasonEnum: int
{
    case SPRING = 3;
    case SUMMER = 6;
    case FALL = 9;
    case WINTER = 12;

    /**
     * Return the name (lowercase) of the season.
     */
    public function getName(): string
    {
        return strtolower($this->name);
    }

    /**
     * Return the month number (from 1 = January to 12 = December) when starts the season.
     */
    public function getStartMonth(): int
    {
        return $this->value;
    }

    /**
     * Create a season from the month number (from 1 = January to 12 = December) when starts the season.
     */
    public static function fromStartMonth(int $month): self
    {
        return self::from($month);
    }

    /**
     * Create a season from its name.
     */
    public static function fromName(string $name): self
    {
        $name = strtoupper($name);
        $reflection = new ReflectionEnum(self::class);

        return $reflection->hasCase($name)
            ? $reflection->getCase($name)->getValue()
            : throw new ValueError(
                $name . ' is not a valid backing name for enum ' . self::class,
            );
    }
}
