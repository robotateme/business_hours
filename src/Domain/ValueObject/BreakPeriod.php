<?php

namespace BusinessHours\Domain\ValueObject;

final class BreakPeriod
{
    public function __construct(
        public TimeRange $range,
        public string $reason
    ) {}

    public function isNow(SecondOfDay $time): bool
    {
        return $this->range->contains($time);
    }

    public function start(): int
    {
        return $this->range->start();
    }

    public function end(): int
    {
        return $this->range->end();
    }
}