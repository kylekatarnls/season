<?php

namespace Tests\Season;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Season\Season;

class SeasonTest extends TestCase
{
    const DATE_CLASS = DateTimeImmutable::class;

    public function testTimezone(): void
    {
        $dateClass = static::DATE_CLASS;
        self::assertTrue((new Season())->isInSummer(new $dateClass('2022-06-21 02:30', new DateTimeZone('Asia/Tokyo'))));
        self::assertTrue((new Season())->isInSummer(new $dateClass('2022-06-21 02:30', new DateTimeZone('America/Los_Angeles'))));
        self::assertTrue((new Season())->isInSummer('2022-06-21 02:30'));
        self::assertSame(
            '2022-03-20 00:00:00.000000 America/Los_Angeles',
            (new Season())->startOfSeason(new $dateClass('2022-06-21 02:30', new DateTimeZone('Asia/Tokyo')), 'America/Los_Angeles')
                ->format('Y-m-d H:i:s.u e'),
        );
        self::assertSame(
            '2022-06-21 00:00:00.000000 Asia/Tokyo',
            (new Season())->startOfSeason(new $dateClass('2022-06-21 02:30', new DateTimeZone('America/Los_Angeles')), 'Asia/Tokyo')
                ->format('Y-m-d H:i:s.u e'),
        );
        self::assertSame(
            '2022-06-21 00:00:00.000000 UTC',
            (new Season())->startOfSeason('2022-06-21 02:30')
                ->format('Y-m-d H:i:s.u e'),
        );
        self::assertSame(
            '2022-06-21 00:00:00.000000 Asia/Tokyo',
            (new Season())->startOfSeason('2022-06-21 02:30', 'Asia/Tokyo')
                ->format('Y-m-d H:i:s.u e'),
        );
        self::assertSame(
            '2022-06-21 00:00:00.000000 America/Los_Angeles',
            (new Season())->startOfSeason('2022-06-21 02:30', 'America/Los_Angeles')
                ->format('Y-m-d H:i:s.u e'),
        );
        self::assertSame(
            '2022-06-21 00:00:00.000000 America/Los_Angeles',
            (new Season())->startOfSeason('2022-06-21 02:30 America/Los_Angeles', 'Asia/Tokyo')
                ->format('Y-m-d H:i:s.u e'),
        );
        self::assertSame(
            '2022-06-21 00:00:00.000000 Asia/Tokyo',
            (new Season())->startOfSeason('2022-06-21 02:30 Asia/Tokyo', 'America/Los_Angeles')
                ->format('Y-m-d H:i:s.u e'),
        );
    }

    public function testStartAndEndOfSeason(): void
    {
        $dateClass = static::DATE_CLASS;
        $period = new DatePeriod(
            new DateTimeImmutable('2022-12-21 12:00'),
            new DateInterval('P1D'),
            new DateTimeImmutable('2023-12-21 12:00'),
        );
        $winter2022 = new DateTimeImmutable('2022-12-21 00:00');
        $spring2023 = new DateTimeImmutable('2023-03-20 00:00');
        $summer2023 = new DateTimeImmutable('2023-06-21 00:00');
        $fall2023 = new DateTimeImmutable('2023-09-22 00:00');
        $winter2023 = new DateTimeImmutable('2023-12-21 00:00');
        $spring2024 = new DateTimeImmutable('2024-03-20 00:00');

        $winter2022String = $winter2022->format('Y-m-d H:i:s.u');
        $winter2022EndString = $spring2023->modify('-1 microsecond')->format('Y-m-d H:i:s.u');

        $spring2023String = $spring2023->format('Y-m-d H:i:s.u');
        $spring2023EndString = $summer2023->modify('-1 microsecond')->format('Y-m-d H:i:s.u');

        $summer2023String = $summer2023->format('Y-m-d H:i:s.u');
        $summer2023EndString = $fall2023->modify('-1 microsecond')->format('Y-m-d H:i:s.u');

        $fall2023String = $fall2023->format('Y-m-d H:i:s.u');
        $fall2023EndString = $winter2023->modify('-1 microsecond')->format('Y-m-d H:i:s.u');

        $winter2023String = $winter2023->format('Y-m-d H:i:s.u');
        $winter2023EndString = $spring2024->modify('-1 microsecond')->format('Y-m-d H:i:s.u');

        $season = new Season();

        foreach ($period as $step) {
            $date = new $dateClass($step->format('Y-m-d H:i'));
            $clone = clone $date;
            $start = $season->startOfSeason($date);
            $end = $season->endOfSeason($clone);

            self::assertModifiedDate($start, $date);
            self::assertModifiedDate($end, $clone);

            if ($start < $spring2023) {
                self::assertSame($winter2022String, $start->format('Y-m-d H:i:s.u'));
                self::assertSame($winter2022EndString, $end->format('Y-m-d H:i:s.u'));

                continue;
            }

            if ($start < $summer2023) {
                self::assertSame($spring2023String, $start->format('Y-m-d H:i:s.u'));
                self::assertSame($spring2023EndString, $end->format('Y-m-d H:i:s.u'));

                continue;
            }

            if ($start < $fall2023) {
                self::assertSame($summer2023String, $start->format('Y-m-d H:i:s.u'));
                self::assertSame($summer2023EndString, $end->format('Y-m-d H:i:s.u'));

                continue;
            }

            if ($start < $winter2023) {
                self::assertSame($fall2023String, $start->format('Y-m-d H:i:s.u'));
                self::assertSame($fall2023EndString, $end->format('Y-m-d H:i:s.u'));

                continue;
            }

            self::assertSame($winter2023String, $start->format('Y-m-d H:i:s.u'));
            self::assertSame($winter2023EndString, $end->format('Y-m-d H:i:s.u'));
        }
    }

    private static function assertModifiedDate(DateTimeInterface $expected, DateTimeInterface $actual): void
    {
        self::assertSame(static::DATE_CLASS, \get_class($expected));

        if ($actual instanceof DateTimeImmutable) {
            self::assertNotSame($expected, $actual);

            return;
        }

        self::assertSame($expected, $actual);
    }
}
