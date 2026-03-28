<?php

namespace BusinessHours\Domain;

use BusinessHours\Domain\Values\Status;
use DateTimeImmutable;

final class ScheduleChecker
{
    public function check(Schedule $schedule, DateTimeImmutable $time): Status
    {
        return $schedule->getStatus($time);
    }
}
