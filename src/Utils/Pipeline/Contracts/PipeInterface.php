<?php

namespace BusinessHours\Utils\Pipeline\Contracts;

use BusinessHours\Utils\Pipeline\Payload;

interface PipeInterface
{
    public function execute(Payload $payload, callable $callback): Payload;
}