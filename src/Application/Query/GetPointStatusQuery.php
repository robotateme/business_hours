<?php

namespace BusinessHours\Application\Query;

use BusinessHours\Domain\Schedule;
use DateTimeImmutable;

final class GetPointStatusQuery
{
    public function __construct(
        public Schedule $schedule,
        public DateTimeImmutable $time
    ) {}
}