<?php

declare(strict_types=1);

namespace Season;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class Season
{
    public const SPRING = SeasonEnum::SPRING;
    public const SUMMER = SeasonEnum::SUMMER;
    public const FALL = SeasonEnum::FALL;
    public const WINTER = SeasonEnum::WINTER;

    private readonly array $config;

    private const DEFAULT_CONFIG = [
        3 => 20, // spring
        6 => 21, // summer
        9 => 22, // fall
        12 => 21, // winter
    ];

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? self::DEFAULT_CONFIG;
    }

    /**
     * Return the season of a given date (now by default).
     */
    public function getSeason(DateTimeInterface|string $dateTime = 'now'): SeasonEnum
    {
        $now = $this->makeDateTime($dateTime);

        $month = (int) $now->format('n');
        $day = (int) $now->format('j');
        $dayInCurrentMonth = $this->config[$month] ?? null;

        return SeasonEnum::fromStartMonth(
            $dayInCurrentMonth && $day >= $dayInCurrentMonth
                ? $month
                : $this->getPreviousStartMonth($month),
        );
    }

    /**
     * Return either a given date (now by default) is in spring.
     */
    public function isInSpring(DateTimeInterface|string $dateTime = 'now'): bool
    {
        return $this->getSeason($dateTime) === self::SPRING;
    }

    /**
     * Return either a given date (now by default) is in summer.
     */
    public function isInSummer(DateTimeInterface|string $dateTime = 'now'): bool
    {
        return $this->getSeason($dateTime) === self::SUMMER;
    }

    /**
     * Return either a given date (now by default) is in fall.
     */
    public function isInFall(DateTimeInterface|string $dateTime = 'now'): bool
    {
        return $this->getSeason($dateTime) === self::FALL;
    }

    /**
     * Return either a given date (now by default) is in winter.
     */
    public function isInWinter(DateTimeInterface|string $dateTime = 'now'): bool
    {
        return $this->getSeason($dateTime) === self::WINTER;
    }

    /**
     * Return the date when the season of a given date (now by default) starts.
     */
    public function startOfSeason(
        DateTimeInterface|string $dateTime = 'now',
        DateTimeZone|string|null $timeZone = null,
    ): DateTimeInterface {
        $now = $this->makeDateTime($dateTime, $timeZone);

        $year = (int) $now->format('Y');
        $month = (int) $now->format('n');
        $day = (int) $now->format('j');
        $dayInCurrentMonth = $this->config[$month] ?? null;

        if ($dayInCurrentMonth && $day >= $dayInCurrentMonth) {
            return $now
                ->setDate($year, $month, $dayInCurrentMonth)
                ->setTime(0, 0, 0, 0);
        }

        $expectedMonth = $this->getPreviousStartMonth($month);
        $expectedDay = $this->config[$expectedMonth];
        $expectedYear = $year - ($expectedMonth > $month ? 1 : 0);

        return $now
            ->setDate($expectedYear, $expectedMonth, $expectedDay)
            ->setTime(0, 0, 0, 0);
    }

    /**
     * Return the date when the season of a given date (now by default) ends.
     */
    public function endOfSeason(
        DateTimeInterface|string $dateTime = 'now',
        DateTimeZone|string|null $timeZone = null,
    ): DateTimeInterface {
        $now = $this->makeDateTime($dateTime, $timeZone);

        $year = (int) $now->format('Y');
        $month = (int) $now->format('n');
        $day = (int) $now->format('j');
        $dayInCurrentMonth = $this->config[$month] ?? null;

        if ($dayInCurrentMonth && $day < $dayInCurrentMonth) {
            return $now
                ->setDate($year, $month, $dayInCurrentMonth - 1)
                ->setTime(23, 59, 59, 999999);
        }

        $expectedMonth = ($month + (3 - $month % 3) - 3) % 12 + 3;
        $expectedDay = $this->config[$expectedMonth] - 1;
        $expectedYear = $year + ($expectedMonth < $month ? 1 : 0);

        return $now
            ->setDate($expectedYear, $expectedMonth, $expectedDay)
            ->setTime(23, 59, 59, 999999);
    }

    private function makeDateTime(
        DateTimeInterface|string $dateTime,
        DateTimeZone|string|null $timeZone = null,
    ): DateTimeInterface {
        if (is_string($timeZone)) {
            $timeZone = new DateTimeZone($timeZone);
        }

        if (is_string($dateTime)) {
            return new DateTimeImmutable($dateTime, $timeZone);
        }

        if ($timeZone !== null) {
            return $dateTime->setTimezone($timeZone);
        }

        return $dateTime;
    }

    private function getPreviousStartMonth(int $month): int
    {
        $previousStartMonth = ((int) floor(($month - 1) / 3)) * 3;

        return $previousStartMonth <= 0 ? 12 : $previousStartMonth;
    }
}
