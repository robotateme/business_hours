<?php

namespace BusinessHours\Utils;

use BusinessHours\Values\Duration;
use BusinessHours\Values\SecondsAfterMidnight;
use DateTimeImmutable;
use DateTimeInterface;

readonly class TimeInterval
{
    /**
     * @param DateTimeInterface $begin
     * @param DateTimeInterface $end
     */
    public function __construct(
        private DateTimeInterface $begin,
        private DateTimeInterface $end
    )
    {
    }

    /**
     * @param DateTimeImmutable $now
     * @return bool
     */
    public function contains(DateTimeImmutable $now): bool
    {

        $nowSec = (new SecondsAfterMidnight($now))->getValue();
        $beginSec = (new SecondsAfterMidnight($this->begin))->getValue();
        $endSec = (new SecondsAfterMidnight($this->end))->getValue();

        return $beginSec <= $endSec
            ? $nowSec >= $beginSec && $nowSec < $endSec
            : $nowSec >= $beginSec || $nowSec < $endSec;
    }

    /**
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable $to
     * @return int
     */
    public static function durationSecond(DateTimeImmutable $from, DateTimeImmutable $to): int
    {
        $fromSec = (new SecondsAfterMidnight($from))->getValue();
        $toSec = (new SecondsAfterMidnight($to))->getValue();

        return ($toSec - $fromSec + 86400) % 86400 ?: 86400;
    }

    public static function humanDuration(int $duration): int
    {
        return (new Duration($duration))->human();
    }


}
