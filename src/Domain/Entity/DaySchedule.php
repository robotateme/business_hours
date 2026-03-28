<?php

namespace BusinessHours\Domain\Entity;

use BusinessHours\Domain\Values\BreakPeriod;
use BusinessHours\Domain\Values\Status;
use BusinessHours\Domain\Values\TimeRange;
use DateTimeImmutable;

final class DaySchedule
{
    /** @var BreakPeriod[] */
    public array $breaks = [];

    public function __construct(
        public TimeRange $workingHours,
        array $breaks = []
    ) {
        $this->breaks = $breaks;
    }

    public function getStatus(DateTimeImmutable $time): Status
    {
        if (!$this->workingHours->contains($time)) {
            return new Status(Status::CLOSED);
        }

        foreach ($this->breaks as $break) {
            if ($break->isNow($time)) {
                return new Status(Status::ON_BREAK, $break->reason);
            }
        }

        return new Status(Status::OPEN);
    }
}