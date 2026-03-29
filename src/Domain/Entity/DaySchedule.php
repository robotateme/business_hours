<?php

namespace BusinessHours\Domain\Entity;

use BusinessHours\Domain\ValueObject\BreakPeriod;
use BusinessHours\Domain\ValueObject\SecondOfDay;
use BusinessHours\Domain\ValueObject\Status;
use BusinessHours\Domain\ValueObject\TimeRange;

final readonly class DaySchedule
{
    /** @var BreakPeriod[] */
    private array $breaks;

    public function __construct(
        private TimeRange $workingHours,
        array             $breaks = []
    )
    {
        $this->breaks = $breaks;
    }

    public function resolve(SecondOfDay $time): Status
    {
        $t = $time->value();

        // 1. BREAK имеет приоритет
        foreach ($this->breaks as $break) {
            if ($break->range->contains($time)) {
                return Status::onBreak(
                    $break->reason,
                    $this->secondsDiff($t, $break->range->end())
                );
            }
        }

        // 2. OPEN
        if ($this->workingHours->contains($time)) {
            $toClose = $this->secondsDiff($t, $this->workingHours->end());
            $nextBreak = $this->nextBreakAfter($t);
            return Status::open($toClose, $nextBreak);
        }

        // 3. CLOSED → считаем когда откроется
        $nextOpen = $this->secondsUntilOpen($t);

        return Status::closed($nextOpen);
    }

    private function nextBreakAfter(int $t): ?int
    {
        $future = [];

        foreach ($this->breaks as $break) {
            $start = $break->range->start();

            if ($start >= $t) {
                $future[] = $start;
            }
        }

        if ($future === []) {
            return null;
        }

        return $this->secondsDiff($t, min($future));
    }

    private function secondsUntilOpen(int $t): int
    {
        $start = $this->workingHours->start();

        return $this->secondsDiff($t, $start);
    }

    private function secondsDiff(int $now, int $target): int
    {
        if ($target >= $now) {
            return $target - $now;
        }

        return 86400 - $now + $target;
    }

}