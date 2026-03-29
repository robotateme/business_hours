<?php

namespace BusinessHours\Domain\Entity;

use BusinessHours\Application\DTO\Status;
use BusinessHours\Domain\ValueObject\SecondOfDay;

final class Schedule
{
    /** @var array<string, DaySchedule> */
    private array $days;

    public function __construct(array $days)
    {
        $this->days = $days;
    }

    public function getStatus(string $day, SecondOfDay $time): Status
    {
        if (!isset($this->days[$day])) {
            return Status::closed(86400); // fallback
        }

        return $this->days[$day]->resolve($time);
    }
}