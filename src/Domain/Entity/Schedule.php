<?php

namespace BusinessHours\Domain\Entity;

use BusinessHours\Application\Enum\StatusType;
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

    /**
     * @param string $day
     * @param SecondOfDay $time
     * @return Status
     */
    public function getStatus(string $day, SecondOfDay $time): Status
    {
        $current = $this->days[$day] ?? null;

        if ($current !== null) {
            return $current->resolve($time);
        }

        $prev = $this->days[$this->previousDay($day)] ?? null;

        if ($prev !== null) {
            $status = $prev->resolve($time);

            if ($status->type !== StatusType::CLOSED) {
                return $status;
            }
        }

        return Status::closed(86400);
    }

    private const array DAYS = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

    private function previousDay(string $day): string
    {
        $i = array_search($day, self::DAYS, true);
        return self::DAYS[($i - 1 + 7) % 7];
    }
}