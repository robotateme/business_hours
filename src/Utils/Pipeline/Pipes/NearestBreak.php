<?php

namespace BusinessHours\Utils\Pipeline\Pipes;

use BusinessHours\Utils\Pipeline\Contracts\PipeInterface;
use BusinessHours\Utils\Pipeline\Payload;
use DateMalformedStringException;

class NearestBreak implements PipeInterface
{

    /**
     * @param Payload $payload
     * @param callable $callback
     * @return Payload
     * @throws DateMalformedStringException
     */
    public function execute(Payload $payload, callable $callback): Payload
    {
        if ($payload->daySchedule->hasBreaks()) {
            $closestBreak = $payload->daySchedule
                ->getBreaks()
                ->getNearestByTime($payload->now);
        }

        return $callback($payload);
    }
}