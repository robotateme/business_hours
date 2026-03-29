<?php
namespace BusinessHours\Tests;

use BusinessHours\Application\Enum\StatusType;
use BusinessHours\Application\Query\GetPointStatusHandler;
use BusinessHours\Application\Query\GetPointStatusQuery;
use BusinessHours\Domain\Entity\DaySchedule;
use BusinessHours\Domain\Entity\Schedule;
use BusinessHours\Domain\ValueObject\Exceptions\InvalidTimeException;
use BusinessHours\Domain\ValueObject\SecondOfDay;
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

        $this->assertEquals(StatusType::OPEN, $status->type);
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

        $this->assertEquals(StatusType::ON_BREAK, $status->type);
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

        $this->assertEquals(StatusType::CLOSED, $status->type);
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

        $this->assertEquals(StatusType::ON_BREAK, $status->type);
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

        $this->assertEquals(StatusType::ON_BREAK, $status->type);
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

        $this->assertEquals(StatusType::OPEN, $status->type);
        $this->assertEquals(600, $status->secondsToBreak);
    }

    public function test_invalid_time_format(): void
    {
        $this->expectException(InvalidTimeException::class);

        SecondOfDay::fromString('25:00');
    }

    public function test_invalid_seconds(): void
    {
        $this->expectException(InvalidTimeException::class);

        SecondOfDay::fromInt(90000);
    }

    public function test_exact_open_time(): void
    {
        $schedule = Factory::petShop();

        $status = $schedule->getStatus(
            'Mon',
            SecondOfDay::fromString('08:00')
        );

        $this->assertEquals(StatusType::OPEN, $status->type);
    }

    public function test_exact_close_time(): void
    {
        $schedule = Factory::petShop();

        $status = $schedule->getStatus(
            'Mon',
            SecondOfDay::fromString('18:00')
        );

        $this->assertEquals(StatusType::CLOSED, $status->type);
    }

    public function test_exact_break_start(): void
    {
        $schedule = Factory::petShop();

        $status = $schedule->getStatus(
            'Mon',
            SecondOfDay::fromString('12:00')
        );

        $this->assertEquals(StatusType::ON_BREAK, $status->type);
    }

    public function test_cross_midnight_edge(): void
    {
        $schedule = Factory::gasStation();

        $status = $schedule->getStatus(
            'Sun',
            SecondOfDay::fromString('23:00')
        );

        $this->assertEquals(StatusType::ON_BREAK, $status->type);
    }

    public function test_missing_day(): void
    {
        $schedule = new Schedule([]);

        $status = $schedule->getStatus(
            'Mon',
            SecondOfDay::fromString('10:00')
        );

        $this->assertEquals(StatusType::CLOSED, $status->type);
    }

    public function test_prediction_with_seconds_precision(): void
    {
        $schedule = Factory::petShop();
        $handler = new GetPointStatusHandler();

        $status = $handler->handle(
            new GetPointStatusQuery(
                $schedule,
                'Mon',
                new DateTimeImmutable('11:59:30')
            )
        );

        $this->assertEquals(StatusType::OPEN, $status->type);
        $this->assertEquals(30, $status->secondsToBreak);
    }

}