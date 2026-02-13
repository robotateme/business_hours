<?php

namespace BusinessHours\Entity\Iterators;

use BusinessHours\Entity\WorkBreak;
use DateMalformedStringException;
use DateTimeImmutable;

class BreaksCollection extends ScheduleCollection
{
    /**
     * @param DateTimeImmutable $now
     * @return WorkBreak
     * @throws DateMalformedStringException
     */
    public function getNearestByTime(DateTimeImmutable $now): WorkBreak
    {
        $normalized = $this->normalize();
        dd($normalized);
    }

    /**
     * @throws DateMalformedStringException
     */
    private function normalize(): array
    {
        $normalized = [];
        /** @var WorkBreak $break */
        foreach ($this->getArrayCopy() as $break) {
            $normalized[$break->getBeginInSec()] = $break;
        }

        return $normalized;
    }
}