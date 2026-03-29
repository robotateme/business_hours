<?php

namespace BusinessHours\Application\DTO;

final class Status
{
    private function __construct(
        public string $type,
        public ?string $reason = null,
        public ?int $secondsToOpen = null,
        public ?int $secondsToClose = null,
        public ?int $secondsToBreak = null,
        public ?int $secondsToResume = null
    ) {}

    public static function open(?int $toClose = null, ?int $toBreak = null): self
    {
        return new self('OPEN', null, null, $toClose, $toBreak);
    }

    public static function closed(int $toOpen): self
    {
        return new self('CLOSED', null, $toOpen);
    }

    public static function onBreak(string $reason, int $toResume): self
    {
        return new self('ON_BREAK', $reason, null, null, null, $toResume);
    }
}