<?php

namespace BusinessHours\Entity;

use BusinessHours\Entity\Iterators\BreaksCollection;
use BusinessHours\Entity\Traits\TimeHelper;
use BusinessHours\Enums\WeekDaysEnum;

class DaySchedule
{
    use TimeHelper;
    private WeekDaysEnum $dayName;
    private string $begin = '00:00';
    private string $end = '00:00';

    private BreaksCollection $breaks;

    /**
     * @param string $begin
     * @return $this
     */
    public function setBegin(string $begin): self
    {
        $this->begin = $begin;
        return $this;
    }

    /**
     * @return string
     */
    public function getBegin(): string
    {
        return $this->begin;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->begin;
    }

    /**
     * @param string $end
     * @return $this
     */
    public function setEnd(string $end): self
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param WeekDaysEnum $dayName
     * @return $this
     */
    public function setDayName(WeekDaysEnum $dayName): self
    {
        $this->dayName = $dayName;
        return $this;
    }

    /**
     * @return WeekDaysEnum
     */
    public function getDayName(): WeekDaysEnum
    {
        return $this->dayName;
    }

    /**
     * @param BreaksCollection $breaks
     * @return $this
     */
    public function setBreaks(BreaksCollection $breaks): self
    {
        $this->breaks = $breaks;
        return $this;
    }

    /**
     * @return BreaksCollection
     */
    public function getBreaks(): BreaksCollection
    {
        return $this->breaks;
    }

    /**
     * @return bool
     */
    public function hasBreaks(): bool
    {
        return $this->breaks->count() > 0;
    }
}