<?php

namespace BusinessHours\Entity\Traits;

use BusinessHours\Utils\TimeInterval;
use BusinessHours\Values\SecondsAfterMidnight;
use DateMalformedStringException;
use DateTimeImmutable;

trait TimeHelper
{
    /**
     * @return int
     * @throws DateMalformedStringException
     */
    public function getEndInSec(): int
    {
        return (new SecondsAfterMidnight($this->getEndDateTime()))->getValue();
    }

    /**
     * @return DateTimeImmutable
     * @throws DateMalformedStringException
     */
    public function getEndDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->end);
    }

    /**
     * @return int
     * @throws DateMalformedStringException
     */
    public function getBeginInSec(): int
    {
        return (new SecondsAfterMidnight($this->getBeginDateTime()))->getValue();
    }

    /**
     * @return DateTimeImmutable
     * @throws DateMalformedStringException
     */
    public function getBeginDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->begin);
    }


    /**
     * @param DateTimeImmutable $now
     * @return int
     * @throws DateMalformedStringException
     */
    public function secondsToEnd(DateTimeImmutable $now): int
    {
        return TimeInterval::durationSecond($now, $this->getEndDateTime());
    }

    /**
     * @param DateTimeImmutable $now
     * @return int
     * @throws DateMalformedStringException
     */
    public function secondsToBegin(DateTimeImmutable $now): int
    {
        return TimeInterval::durationSecond($now, $this->getBeginDateTime());
    }

    /**
     * @return bool
     * @throws DateMalformedStringException
     */
    public function isAcrossMidnight(): bool
    {
        return $this->getEndInSec() < $this->getBeginInSec();
    }

    /**
     * @throws DateMalformedStringException
     */
    public function contains(DateTimeImmutable $now): bool
    {
        $interval = new TimeInterval(
            $this->getBeginDateTime(),
            $this->getEndDateTime()
        );

        return $interval->contains($now);
    }
}