<?php

namespace BusinessHours\Utils;

use BusinessHours\Entity\Iterators\BreaksCollection;
use BusinessHours\Entity\Iterators\WeekScheduleCollection;
use BusinessHours\Entity\DaySchedule;
use BusinessHours\Entity\WorkBreak;
use BusinessHours\Enums\WeekDaysEnum;

class ScheduleFactory
{
    /**
     * @param array $scheduleArray
     * @return WeekScheduleCollection
     */
    public static function make(array $scheduleArray): WeekScheduleCollection
    {
        $schedule = new WeekScheduleCollection();
        foreach ($scheduleArray as $day => $businessHoursArray) {
            $workingDay = new DaySchedule();
            $breaks = new BreaksCollection(array_map(static function ($breakArray) {
                return (new WorkBreak())
                    ->setBegin($breakArray['begin'])
                    ->setEnd($breakArray['end'])
                    ->setReason($breakArray['reason']);
            }, $businessHoursArray['breaks']));

            $workingDay->setDayName(WeekDaysEnum::tryFrom($day))
                ->setBegin($businessHoursArray['begin'])
                ->setEnd($businessHoursArray['end'])
                ->setBreaks($breaks);

            $schedule->offsetSet($day, $workingDay);
        }

        return $schedule;
    }
}