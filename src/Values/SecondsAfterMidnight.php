<?php

namespace BusinessHours\Values;

use DateTimeInterface;

final readonly class SecondsAfterMidnight
{
    public function __construct(private DateTimeInterface $value)
    {
    }

    public function getValue(): int
    {
        return (int)$this->value->format('H') * 3600
            + (int)$this->value->format('i') * 60
            + (int)$this->value->format('s');
    }
}