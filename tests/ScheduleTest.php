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
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function test_open_boundary(): void
    {
        $schedule = Factory::petShop();

        // 07:59:59
        $this->assertSame(
            StatusType::CLOSED,
            $schedule->getStatus('Mon', SecondOfDay::fromInt(7*3600 + 59*60 + 59))->type
        );

        // 08:00:00
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Mon', SecondOfDay::fromString('08:00'))->type
        );

        // 08:00:01
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Mon', SecondOfDay::fromInt(8*3600 + 1))->type
        );
    }

    public function test_break_boundary(): void
    {
        $schedule = Factory::petShop();

        // 11:59:59
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Mon', SecondOfDay::fromInt(11*3600 + 59*60 + 59))->type
        );

        // 12:00
        $this->assertSame(
            StatusType::ON_BREAK,
            $schedule->getStatus('Mon', SecondOfDay::fromString('12:00'))->type
        );

        // 13:00
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Mon', SecondOfDay::fromString('13:00'))->type
        );
    }

    public function test_midnight_transition(): void
    {
        $schedule = Factory::gasStation();

        // 23:59:59
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Mon', SecondOfDay::fromInt(86399))->type
        );

        // 00:00:00
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Tue', SecondOfDay::fromInt(0))->type
        );
    }

    public function test_cross_midnight_break(): void
    {
        $schedule = Factory::gasStation();

        // 23:30
        $this->assertSame(
            StatusType::CLOSED,
            $schedule->getStatus('Sat', SecondOfDay::fromString('23:30'))->type
        );

        // 04:59
        $this->assertSame(
            StatusType::ON_BREAK,
            $schedule->getStatus('Sun', SecondOfDay::fromString('04:59'))->type
        );

        // 05:00
        $this->assertSame(
            StatusType::OPEN,
            $schedule->getStatus('Sun', SecondOfDay::fromString('05:00'))->type
        );
    }

    public static function boundaryProvider(): array
    {
        return [
            ['07:59', StatusType::CLOSED],
            ['08:00', StatusType::OPEN],
            ['11:59', StatusType::OPEN],
            ['12:00', StatusType::ON_BREAK],
        ];
    }


    #[DataProvider('boundaryProvider')]
    public function test_boundaries(string $time, StatusType $expected): void
    {
        $schedule = Factory::petShop();

        $status = $schedule->getStatus(
            'Mon',
            SecondOfDay::fromString($time)
        );

        $this->assertSame($expected, $status->type);
    }

    public function test_break_end_boundary(): void
    {
        $schedule = Factory::petShop();

        // 12:59:59
        $statusBefore = $schedule->getStatus('Mon', SecondOfDay::fromInt(46799));
        $this->assertSame(1, $statusBefore->secondsToResume);

        // 13:00:00
        $statusAt = $schedule->getStatus('Mon', SecondOfDay::fromInt(46800));

        $this->assertSame(StatusType::OPEN, $statusAt->type);
        $this->assertNull( $statusAt->secondsToResume);
        $this->assertSame(18000, $statusAt->secondsToClose);
    }

    public function test_break_boundary_seconds(): void
    {
        $schedule = Factory::petShop();

        // 11:59:59 → OPEN
        $statusBefore = $schedule->getStatus('Mon', SecondOfDay::fromInt(43199));
        $this->assertSame(1, $statusBefore->secondsToBreak);

        // 12:00:00 → ON_BREAK
        $statusAt = $schedule->getStatus('Mon', SecondOfDay::fromInt(43200));
        $this->assertSame(3600, $statusAt->secondsToResume);
    }

    public function test_closed_seconds_to_open_precision(): void
    {
        $schedule = Factory::petShop();

        // 07:59:30
        $status = $schedule->getStatus('Mon', SecondOfDay::fromInt(28770));
        $this->assertSame(30, $status->secondsToOpen);
    }

}