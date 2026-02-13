<?php

namespace BusinessHours\Values;

final readonly class Duration
{
    public function __construct(
        public int $seconds
    )
    {
    }

    public function hours(): int
    {
        return intdiv($this->seconds, 3600);
    }

    public function minutes(): int
    {
        return intdiv($this->seconds % 3600, 60);
    }

    public function human(): string
    {
        $timeString = sprintf('%d:%d', $this->hours(), $this->minutes());
        return $this->hours() < 10?: $timeString;
    }
}