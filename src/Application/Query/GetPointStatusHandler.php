<?php

namespace BusinessHours\Application\Query;

use BusinessHours\Domain\ValueObject\SecondOfDay;
use BusinessHours\Domain\ValueObject\Status;

final class GetPointStatusHandler
{
    public function handle(GetPointStatusQuery $q): Status
    {
        $seconds = ((int)$q->time->format('H') * 3600)
            + ((int)$q->time->format('i') * 60)
            + ((int)$q->time->format('s'))
        ;

        $time = SecondOfDay::fromInt($seconds);

        return $q->schedule->getStatus($q->day, $time);
    }
}