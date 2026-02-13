<?php

namespace BusinessHours\Utils\Pipeline;

use BusinessHours\Entity\DaySchedule;
use BusinessHours\Utils\ScheduleOperationStatus;
use DateTimeImmutable;

class Payload
{
    public function __construct(
        public DateTimeImmutable $now,
        public DaySchedule $daySchedule,
        public ScheduleOperationStatus $operationStatus,
    )
    {
    }
}