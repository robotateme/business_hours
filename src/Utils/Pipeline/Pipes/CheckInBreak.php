<?php

namespace BusinessHours\Utils\Pipeline\Pipes;

use BusinessHours\Entity\WorkBreak;
use BusinessHours\Utils\Pipeline\Contracts\PipeInterface;
use BusinessHours\Utils\Pipeline\Payload;

class CheckInBreak implements PipeInterface
{
    /**
     * @param Payload $payload
     * @param callable $callback
     * @return Payload
     * @throws \DateMalformedStringException
     */
    public function execute(Payload $payload, callable $callback): Payload
    {
        if ($payload->daySchedule->hasBreaks()) {
            /** @var WorkBreak $break */
            foreach ($payload->daySchedule->getBreaks() as $break) {
                if ($break->contains($payload->now)) {
                    $payload->operationStatus
                        ->markAsInBreak($break->secondsToEnd(
                            $payload->now),
                            $break->getReason()
                        );
                }
            }
        }

        return $payload;
    }
}