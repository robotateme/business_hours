<?php

namespace BusinessHours\Domain;

use ArrayIterator;
use BusinessHours\Domain\Values\Status;
use DateTimeImmutable;

final class Schedule
{
    /** @var ArrayIterator */
    private ArrayIterator $days;

    public function __construct(array $days)
    {
        $this->days = new ArrayIterator($days);
    }

    public function getStatus(DateTimeImmutable $time): Status
    {
        $day = $time->format('D'); // Mon, Tue...

        if (!isset($this->days[$day])) {
            return new Status(Status::CLOSED);
        }

        return $this->days[$day]->getStatus($time);
    }
}