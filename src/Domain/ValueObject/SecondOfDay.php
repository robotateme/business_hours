<?php
declare(strict_types=1);
namespace BusinessHours\Domain\ValueObject;

use BusinessHours\Domain\ValueObject\Exceptions\InvalidTimeException;

final readonly class SecondOfDay
{
    /**
     * @param int $value
     */
    private function __construct(private int $value) {}

    /**
     * @param string $time
     * @return self
     */
    public static function fromString(string $time): self
    {
        if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
            throw new InvalidTimeException("Invalid format: $time");
        }

        [$h, $m] = explode(':', $time);

        $h = (int)$h;
        $m = (int)$m;

        if ($h < 0 || $h > 23) {
            throw new InvalidTimeException("Invalid hour: $h");
        }

        if ($m < 0 || $m > 59) {
            throw new InvalidTimeException("Invalid minute: $m");
        }

        return new self($h * 3600 + $m * 60);
    }

    /**
     * @param int $seconds
     * @return self
     */
    public static function fromInt(int $seconds): self
    {
        if ($seconds < 0 || $seconds >= 86400) {
            throw new InvalidTimeException("Seconds out of range: $seconds");
        }

        return new self($seconds);
    }

    public function value(): int
    {
        return $this->value;
    }
}