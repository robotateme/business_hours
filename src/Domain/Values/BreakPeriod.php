<?php

namespace BusinessHours\Domain\Values;

use DateTimeImmutable;

final class BreakPeriod
{
    public function __construct(
        public TimeRange $range,
        public string $reason
    ) {}

    public function isNow(DateTimeImmutable $time): bool
    {
        return $this->range->contains($time);
    }
}