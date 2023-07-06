<?php

namespace Season;

use PHPUnit\Framework\TestCase;
use ValueError;

class SeasonEnumTest extends TestCase
{
    public function testFromName(): void
    {
        self::assertSame(Season::SPRING, SeasonEnum::fromName('Spring'));
        self::assertSame(Season::SUMMER, SeasonEnum::fromName('Summer'));
        self::assertSame(Season::FALL, SeasonEnum::fromName('Fall'));
        self::assertSame(Season::WINTER, SeasonEnum::fromName('Winter'));
    }

    public function testFromStartMonth(): void
    {
        self::assertSame(Season::SPRING, SeasonEnum::fromStartMonth(3));
        self::assertSame(Season::SUMMER, SeasonEnum::fromStartMonth(6));
        self::assertSame(Season::FALL, SeasonEnum::fromStartMonth(9));
        self::assertSame(Season::WINTER, SeasonEnum::fromStartMonth(12));
    }

    public function testGetName(): void
    {
        self::assertSame('spring', SeasonEnum::SPRING->getName());
        self::assertSame('summer', SeasonEnum::SUMMER->getName());
        self::assertSame('fall', SeasonEnum::FALL->getName());
        self::assertSame('winter', SeasonEnum::WINTER->getName());
    }
    public function testGetStartMonth(): void
    {
        self::assertSame(3, SeasonEnum::SPRING->getStartMonth());
        self::assertSame(6, SeasonEnum::SUMMER->getStartMonth());
        self::assertSame(9, SeasonEnum::FALL->getStartMonth());
        self::assertSame(12, SeasonEnum::WINTER->getStartMonth());
    }

    public function testFromNameException(): void
    {
        self::expectException(ValueError::class);
        self::expectExceptionMessage('JUNE is not a valid backing name for enum Season\SeasonEnum');
        SeasonEnum::fromName('June');
    }

    public function testFromStartMonthException(): void
    {
        self::expectException(ValueError::class);
        self::expectExceptionMessage('1 is not a valid backing value for enum Season\SeasonEnum');
        SeasonEnum::fromStartMonth(1);
    }
}
