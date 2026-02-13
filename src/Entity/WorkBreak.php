<?php

namespace BusinessHours\Entity;

use BusinessHours\Entity\Traits\TimeHelper;
use BusinessHours\Values\SecondsAfterMidnight;
use DateTimeImmutable;

class WorkBreak
{
    use TimeHelper;

    /**
     * @var string
     */
    private string $begin;
    private string $end;
    private string $reason;

    /**
     * @return string
     */
    public function getBegin(): string
    {
        return $this->begin;
    }

    public function setBegin(string $begin): self
    {
        $this->begin = $begin;
        return $this;
    }

    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @param string $end
     * @return WorkBreak
     */
    public function setEnd(string $end): self
    {
        $this->end = $end;
        return $this;
    }


    /**
     * @param string $reason
     * @return $this
     */
    public function setReason(string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

}