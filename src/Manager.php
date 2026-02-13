<?php

namespace BusinessHours;

use BusinessHours\Entity\Iterators\WeekScheduleCollection;
use BusinessHours\Enums\TimeFormatsEnum;
use BusinessHours\Utils\Pipeline\Payload;
use BusinessHours\Utils\Pipeline\Pipeline;
use BusinessHours\Utils\Pipeline\Pipes\NearestBreak;
use BusinessHours\Utils\Pipeline\Pipes\CheckInBreak;
use BusinessHours\Utils\Pipeline\Pipes\CheckOpen;
use BusinessHours\Utils\Pipeline\Pipes\GetClosestBreak;
use BusinessHours\Utils\ScheduleOperationStatus;
use DateTimeImmutable;

readonly class Manager
{

    public function __construct(private WeekScheduleCollection $weekScheduleCollection)
    {

    }

    public function describeCurrentStatus(
        DateTimeImmutable $now
    ): object
    {
        $dayName = $now->format(TimeFormatsEnum::DAY_NAME->value);
        $daySchedule = $this->weekScheduleCollection->offsetGet($dayName);
        $context = new Payload(
            $now,
            $daySchedule,
            new ScheduleOperationStatus()
        );

        $pipeline = new Pipeline([
            new CheckOpen(),
            new CheckInBreak(),
            new NearestBreak(),
            new GetClosestBreak()
        ]);

        return $pipeline->process($context);
    }
}