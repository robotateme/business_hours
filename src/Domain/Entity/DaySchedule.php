<?php

namespace BusinessHours\Domain\Entity;

use BusinessHours\Application\DTO\Status;
use BusinessHours\Domain\ValueObject\BreakPeriod;
use BusinessHours\Domain\ValueObject\SecondOfDay;
use BusinessHours\Domain\ValueObject\TimeRange;

final readonly class DaySchedule
{
    /** @var BreakPeriod[] */
    private array $breaks;

    public function __construct(
        private TimeRange $workingHours,
        array $breaks = []
    ) {
        $this->breaks = $breaks;
    }

    public function resolve(SecondOfDay $time): Status
    {
        $now = $time->value();

        // закрыто
        if (!$this->workingHours->contains($time)) {
            return Status::closed(
                $this->secondsToWorkingStart($now)
            );
        }

        // перерыв
        foreach ($this->breaks as $break) {
            if ($break->isNow($time)) {
                return Status::onBreak(
                    $break->reason,
                    $this->diff($now, $break->end())
                );
            }
        }

        // открыто
        return Status::open(
            $this->diff($now, $this->workingHoursEnd()),
            $this->nextBreakStart($now)
        );
    }

    private function workingHoursStart(): int
    {
        return $this->workingHours->start();
    }

    private function workingHoursEnd(): int
    {
        return $this->workingHours->end();
    }

    private function secondsToWorkingStart(int $now): int
    {
        return $this->diff($now, $this->workingHoursStart());
    }

    private function diff(int $now, int $target): int
    {
        if ($target >= $now) {
            return $target - $now;
        }

        return (86400 - $now) + $target;
    }

    private function nextBreakStart(int $now): ?int
    {
        $starts = array_map(static fn($b) => $b->start(), $this->breaks);

        $future = array_filter($starts, static fn($s) => $s > $now);

        if (!$future) {
            return null;
        }

        return min($future) - $now;
    }
}