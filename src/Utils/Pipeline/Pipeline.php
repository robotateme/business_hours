<?php

namespace BusinessHours\Utils\Pipeline;

use BusinessHours\Utils\Pipeline\Contracts\PipeInterface;

final readonly class Pipeline
{
    /** @param PipeInterface[] $pipes */
    public function __construct(private array $pipes)
    {
    }

    public function process(Payload $payload): Payload
    {
        $next = static fn($payload) => $payload;
        foreach (array_reverse($this->pipes) as $pipe) {
            $next = static fn($payload) => $pipe->execute($payload, $next);
        }

        return $next($payload);
    }
}