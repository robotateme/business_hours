<?php

namespace BusinessHours\Domain\Values;

final class Status
{
    public const OPEN = 'OPEN';
    public const CLOSED = 'CLOSED';
    public const ON_BREAK = 'ON_BREAK';

    public function __construct(
        public string  $type,
        public ?string $reason = null
    )
    {
    }
}