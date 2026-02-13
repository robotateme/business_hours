<?php

namespace BusinessHours\Entity\Iterators;

use ArrayIterator;
use BusinessHours\Entity\DaySchedule;

/**
 * @method DaySchedule offsetGet(string $key):
 * */
class WeekScheduleCollection extends ScheduleCollection
{
    /**
     * @param string $key
     * @return bool
     */
    public function hasBreaks(string $key): bool
    {
        return $this->offsetGet($key)->hasBreaks();
    }

    /**
     * @param string $key
     * @return ScheduleCollection
     */
    public function getBreaks(string $key): ScheduleCollection
    {
        return $this->offsetGet($key)->getBreaks();
    }
}