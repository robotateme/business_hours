<?php

namespace BusinessHours\Application\Query;

use BusinessHours\Application\DTO\Status;
use BusinessHours\Domain\ValueObject\SecondOfDay;

final class GetPointStatusHandler
{
    public function handle(GetPointStatusQuery $q): Status
    {
        $seconds = ((int)$q->time->format('H') * 3600)
            + ((int)$q->time->format('i') * 60);

        $time = SecondOfDay::fromInt($seconds);

        return $q->schedule->getStatus($q->day, $time);
    }
}