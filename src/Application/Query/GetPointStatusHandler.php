<?php

namespace BusinessHours\Application\Query;

use BusinessHours\Domain\ScheduleChecker;
use BusinessHours\Domain\Values\Status;

final readonly class GetPointStatusHandler
{
    public function __construct(
        private ScheduleChecker $checker
    ) {}

    public function handle(GetPointStatusQuery $query): Status
    {
        return $this->checker->check($query->schedule, $query->time);
    }
}