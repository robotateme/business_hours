<?php
declare(strict_types=1);

namespace BusinessHours\Domain\ValueObject;

use BusinessHours\Application\Enum\StatusType;

final class Status
{
    private function __construct(
        public StatusType $type,
        public ?string    $reason = null,
        public ?int       $secondsToOpen = null,
        public ?int       $secondsToClose = null,
        public ?int       $secondsToBreak = null,
        public ?int       $secondsToResume = null
    )
    {
    }

    /**
     * @param int|null $toClose
     * @param int|null $toBreak
     * @return self
     */
    public static function open(?int $toClose = null, ?int $toBreak = null): self
    {
        return new self(StatusType::OPEN, null, null, $toClose, $toBreak);
    }

    /**
     * @param int $toOpen 'ON_BREAK'
     * @return self
     */
    public static function closed(int $toOpen): self
    {
        return new self(StatusType::CLOSED, null, $toOpen);
    }

    /**
     * @param string $reason
     * @param int $toResume
     * @return self
     */
    public static function onBreak(string $reason, int $toResume): self
    {
        return new self(StatusType::ON_BREAK, $reason, null, null, null, $toResume);
    }
}