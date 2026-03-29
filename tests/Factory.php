<?php
namespace BusinessHours\Tests;

use BusinessHours\Domain\Entity\DaySchedule;
use BusinessHours\Domain\Entity\Schedule;
use BusinessHours\Domain\ValueObject\BreakPeriod;
use BusinessHours\Domain\ValueObject\SecondOfDay;
use BusinessHours\Domain\ValueObject\TimeRange;

final class Factory
{
    public static function petShop(): Schedule
    {
        return new Schedule([
            'Mon' => new DaySchedule(
                new TimeRange(
                    SecondOfDay::fromString('08:00'),
                    SecondOfDay::fromString('18:00')
                ),
                [
                    new BreakPeriod(
                        new TimeRange(
                            SecondOfDay::fromString('12:00'),
                            SecondOfDay::fromString('13:00')
                        ),
                        'Dinner'
                    )
                ]
            )
        ]);
    }

    public static function gasStation(): Schedule
    {
        return new Schedule([
            'Mon' => new DaySchedule(
                new TimeRange(
                    SecondOfDay::fromString('00:00'),
                    SecondOfDay::fromString('00:00')
                ),
                [
                    new BreakPeriod(
                        new TimeRange(
                            SecondOfDay::fromString('01:00'),
                            SecondOfDay::fromString('03:00')
                        ),
                        'Refill'
                    )
                ]
            ),
            'Sun' => new DaySchedule(
                new TimeRange(
                    SecondOfDay::fromString('00:00'),
                    SecondOfDay::fromString('00:00')
                ),
                [
                    new BreakPeriod(
                        new TimeRange(
                            SecondOfDay::fromString('23:00'),
                            SecondOfDay::fromString('05:00')
                        ),
                        'Maintenance'
                    )
                ]
            )
        ]);
    }
}