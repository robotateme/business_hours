<?php
declare(strict_types=1);
namespace BusinessHours\Domain\ValueObject;

final readonly class TimeRange
{
    public function __construct(
        private SecondOfDay $start,
        private SecondOfDay $end
    ) {}

    public function contains(SecondOfDay $time): bool
    {
        $t = $time->value();
        $s = $this->start->value();
        $e = $this->end->value();

        //24/7
        if ($s === $e) {
            return true;
        }

        // обычный диапазон
        if ($s < $e) {
            return $t >= $s && $t < $e;
        }

        //через полночь
        return $t >= $s || $t < $e;
    }

    public function end(): int
    {
        return $this->end->value();
    }

    public function start(): int
    {
        return $this->start->value();
    }
}