<?php
namespace BusinessHours\Tests;

use BusinessHours\Application\Query\GetPointStatusHandler;
use BusinessHours\Application\Query\GetPointStatusQuery;
use BusinessHours\Domain\Entity\DaySchedule;
use BusinessHours\Domain\Entity\Schedule;
use BusinessHours\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Schedule::class)]
#[CoversClass(DaySchedule::class)]
#[CoversClass(TimeRange::class)]
final class ScheduleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_open(): void
    {
        $schedule = Factory::petShop();

        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Mon',
                new DateTimeImmutable('10:00')
            )
        );

        $this->assertEquals('OPEN', $status->type);
    }

    public function test_break(): void
    {
        $schedule = Factory::petShop();

        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Mon',
                new DateTimeImmutable('12:30')
            )
        );

        $this->assertEquals('ON_BREAK', $status->type);
        $this->assertEquals('Dinner', $status->reason);
    }

    public function test_closed(): void
    {
        $schedule = Factory::petShop();

        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Mon',
                new DateTimeImmutable('20:00')
            )
        );

        $this->assertEquals('CLOSED', $status->type);
    }

    public function test_24_7_break(): void
    {
        $schedule = Factory::gasStation();

        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Mon',
                new DateTimeImmutable('02:00')
            )
        );

        $this->assertEquals('ON_BREAK', $status->type);
    }

    public function test_cross_midnight(): void
    {
        $schedule = Factory::gasStation();

        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Sun',
                new DateTimeImmutable('02:00')
            )
        );

        $this->assertEquals('ON_BREAK', $status->type);
    }

    public function test_prediction(): void
    {
        $schedule = Factory::petShop();
        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Mon',
                new DateTimeImmutable('11:50')
            )
        );

        $this->assertEquals('OPEN', $status->type);
        $this->assertEquals(600, $status->secondsToBreak);
    }

}