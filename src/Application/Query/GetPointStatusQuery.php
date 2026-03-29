<?php

namespace BusinessHours\Application\Query;

use BusinessHours\Domain\Entity\Schedule;
use DateTimeImmutable;

final class GetPointStatusQuery
{
    public function __construct(
        public Schedule $schedule,
        public string $day,
        public DateTimeImmutable $time
    ) {}
}