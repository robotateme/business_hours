<?php

namespace BusinessHours\Utils;

use BusinessHours\Enums\StatusesEnum;
use BusinessHours\Values\Duration;

class ScheduleOperationStatus
{
    private StatusesEnum $status = StatusesEnum::STATUS_CLOSED;
    private ?int $timeToOpen = 0;
    private ?int $timeToClose = 0;
    private ?int $timeUntilBreak = 0;
    private ?int $timeUntilResume = 0;
    private ?string $breakReason = '';

    /**
     * @param int $timeToOpen
     * @return self
     */
    public function markAsClosed(int $timeToOpen): self
    {
        $this->status = StatusesEnum::STATUS_CLOSED;
        $this->timeToOpen = $timeToOpen;
        $this->timeToClose = null;
        $this->timeUntilBreak = null;
        $this->timeUntilResume = null;
        $this->breakReason = null;

        return $this;
    }

    /**
     * @param int $timeUntilClose
     * @return self
     */
    public function markAsOpen(int $timeUntilClose): self
    {
        $this->status = StatusesEnum::STATUS_OPEN;
        $this->timeToOpen = null;
        $this->timeToClose = $timeUntilClose;
        $this->timeUntilBreak = null;
        $this->timeUntilResume = null;
        $this->breakReason = null;
        return $this;
    }

    /**
     * @return $this
     */
    public function markAsInBreak(int $timeUntilResume, string $reason): self
    {
        $this->status = StatusesEnum::STATUS_BREAK;
        $this->timeUntilResume = $timeUntilResume;
        $this->timeUntilBreak = null;
        $this->timeToOpen = null;
        $this->breakReason = $reason;
        return $this;
    }

    /**
     * @return StatusesEnum
     */
    public function getStatus(): StatusesEnum
    {
        return $this->status;
    }

    /**
     * @return object
     */
    public function getFullState(): object
    {
        return (object)[
            'status' => $this->status,
            'timeToOpen' => $this->timeToOpen,
            'timeToClose' => $this->timeToClose,
            'timeUntilBreak' => $this->timeUntilBreak,
            'timeUntilResume' => $this->timeUntilResume,
            'reason' => $this->breakReason,
        ];
    }

    /**
     * @param int|null $timeToOpen
     * @return $this
     */
    public function setTimeToOpen(?int $timeToOpen): self
    {
        $this->timeToOpen = $timeToOpen;
        return $this;
    }

    /**
     * @param int|null $timeToClose
     * @return $this
     */
    public function setTimeToClose(?int $timeToClose): self
    {
        $this->timeToClose = $timeToClose;
        return $this;
    }

    /**
     * @param int|null $timeUntilBreak
     * @return $this
     */
    public function setTimeUntilBreak(?int $timeUntilBreak): self
    {
        $this->timeUntilBreak = $timeUntilBreak;
        return $this;
    }

    /**
     * @param int|null $timeUntilResume
     * @return $this
     */
    public function setTimeUntilResume(?int $timeUntilResume): self
    {
        $this->timeUntilResume = $timeUntilResume;
        return $this;
    }

    /**
     * @param string|null $breakReason
     * @return $this
     */
    public function setBreakReason(?string $breakReason): self
    {
        $this->breakReason = $breakReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeToCloseHuman(): string
    {
        return (new Duration($this->timeToClose))->human();
    }
}