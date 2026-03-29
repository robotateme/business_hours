<?php
declare(strict_types=1);
namespace BusinessHours\Domain\ValueObject;

final class SecondOfDay
{
    private function __construct(private int $value) {}

    public static function fromString(string $time): self
    {
        [$h, $m] = explode(':', $time);
        return new self(((int)$h * 3600) + ((int)$m * 60));
    }

    public static function fromInt(int $seconds): self
    {
        return new self($seconds);
    }

    public function value(): int
    {
        return $this->value;
    }
}