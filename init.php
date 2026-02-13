<?php
require "vendor/autoload.php";

use BusinessHours\Manager;
use BusinessHours\Utils\ScheduleFactory;

// Intl.DateTimeFormat().resolvedOptions().timeZone
// В Форме, при выборе "круглосуточно" сделать сброс полей на 00:00 и disable, display:none.

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

$now = new DateTimeImmutable(timezone: new DateTimeZone('Europe/Moscow'));
$schedule = ScheduleFactory::make($petShop);
$manager = new Manager($schedule);
dd($manager->describeCurrentStatus($now)->operationStatus);



