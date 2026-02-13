<?php

namespace BusinessHours\Utils\Pipeline\Pipes;

use BusinessHours\Utils\Pipeline\Contracts\PipeInterface;
use BusinessHours\Utils\Pipeline\Payload;
use BusinessHours\Utils\TimeInterval;
use DateMalformedStringException;

class CheckOpen implements PipeInterface
{
    /**
     * @throws DateMalformedStringException
     */
    public function execute(Payload $payload, callable $callback): Payload
    {
        $timeInterval = new TimeInterval(
            $payload->daySchedule->getBeginDateTime(),
            $payload->daySchedule->getEndDateTime());

        if ($timeInterval->contains($payload->now)) {
            $payload->operationStatus->markAsOpen($payload->daySchedule->secondsToEnd($payload->now));
            return $callback($payload);
        }

        $payload->operationStatus
            ->setTimeToOpen($payload->daySchedule->secondsToBegin($payload->now));

        return $payload;
    }
}