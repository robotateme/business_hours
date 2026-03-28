<?php
require "vendor/autoload.php";

use BusinessHours\Application\Query\GetPointStatusHandler;
use BusinessHours\Application\Query\GetPointStatusQuery;
use BusinessHours\Domain\Entity\DaySchedule;
use BusinessHours\Domain\Schedule;
use BusinessHours\Domain\ScheduleChecker;
use BusinessHours\Domain\Values\BreakPeriod;
use BusinessHours\Domain\Values\TimeRange;

$stripClub = [
    'Mon' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
    'Tue' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
    'Wed' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
    'Thu' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
    'Fri' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
    'Sat' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
    'Sun' => ['begin' => '20:00', 'end' => '08:00', 'breaks' => []],
];

$gasStation = [
    'Mon' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '01:00', 'end' => '03:00', 'reason' => 'Refill'],
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
    ]],
    'Tue' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
    ]],
    'Wed' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
    ]],
    'Thu' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
    ]],
    'Fri' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
        ['begin' => '01:00', 'end' => '03:00', 'reason' => 'Refill'],
    ]],
    'Sat' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '23:00', 'end' => '05:00', 'reason' => 'Maintenance'],
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
    ]],
    'Sun' => ['begin' => '00:00', 'end' => '00:00', 'breaks' => [
        ['begin' => '08:00', 'end' => '08:30', 'reason' => 'Shift change'],
        ['begin' => '12:00', 'end' => '15:30', 'reason' => 'Refill'],
    ]]
];

$petShop = [
    'Mon' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
        ['begin' => '16:00', 'end' => '17:00', 'reason' => 'Goods receipt'],
    ]],
    'Tue' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
    ]],
    'Wed' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
    ]],
    'Thu' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
    ]],
    'Fri' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
        ['begin' => '09:00', 'end' => '10:00', 'reason' => 'Recount'],
    ]],
    'Sat' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
    ]],
    'Sun' => ['begin' => '08:00', 'end' => '18:00', 'breaks' => [
        ['begin' => '12:00', 'end' => '13:00', 'reason' => 'Dinner'],
    ]]
];

/**
 * @throws DateMalformedStringException
 */
$buildSchedule = static function (array $data): Schedule
{
    $days = [];

    foreach ($data as $day => $info) {
        $working = new TimeRange(
            new DateTimeImmutable($info['begin']),
            new DateTimeImmutable($info['end'])
        );

        $breaks = [];

        foreach ($info['breaks'] as $b) {
            $breaks[] = new BreakPeriod(
                new TimeRange(
                    new DateTimeImmutable($b['begin']),
                    new DateTimeImmutable($b['end'])
                ),
                $b['reason']
            );
        }

        $days[$day] = new DaySchedule($working, $breaks);
    }

    return new Schedule($days);
};

$schedule = $buildSchedule($petShop);
$handler = new GetPointStatusHandler(new ScheduleChecker());

$status = $handler->handle(new GetPointStatusQuery(
    $schedule,
    new DateTimeImmutable('now')
));

dd($status->type);

