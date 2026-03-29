<?php

namespace BusinessHours\Domain\Entity;

use BusinessHours\Domain\ValueObject\SecondOfDay;
use BusinessHours\Domain\ValueObject\Status;

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
            // бизнес-решение:
            // нет расписания = всегда закрыто
            return Status::closed(86400);
        }

        return $this->days[$day]->resolve($time);
    }
}