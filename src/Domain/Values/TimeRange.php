<?php

namespace BusinessHours\Domain\Values;

use DateTimeImmutable;

final class TimeRange
{
    public function __construct(
        public DateTimeImmutable $begin,
        public DateTimeImmutable $end
    ) {}

    public function contains(DateTimeImmutable $time): bool
    {
        if ($this->begin <= $this->end) {
            return $time >= $this->begin && $time < $this->end;
        }

        // через полночь (23:00 → 05:00)
        return $time >= $this->begin || $time < $this->end;
    }
}