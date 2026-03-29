# Business Hours

Simple PHP library to determine business status and time to next state change.

## Features

* OPEN / CLOSED / ON_BREAK
* Time to open
* Time to close
* Time to next break
* Time to resume after break
* Supports 24/7 schedules
* Supports cross-midnight ranges
* No DateTime in domain (uses seconds from midnight)

## Usage

```php
$schedule = new Schedule([
    'Mon' => new DaySchedule(
        new TimeRange(
            SecondOfDay::fromString('08:00'),
            SecondOfDay::fromString('18:00')
        ),
        [
            new BreakPeriod(
                new TimeRange(
                    SecondOfDay::fromString('12:00'),
                    SecondOfDay::fromString('13:00')
                ),
                'Dinner'
            )
        ]
    )
]);

$handler = new GetPointStatusHandler();

$status = $handler->handle(
    new GetPointStatusQuery(
        $schedule,
        'Mon',
        new DateTimeImmutable('11:50')
    )
);

echo $status->type; // OPEN
echo $status->secondsToBreak; // 600
```

## Status fields

* type: OPEN | CLOSED | ON_BREAK
* reason: string|null
* secondsToOpen: int|null
* secondsToClose: int|null
* secondsToBreak: int|null
* secondsToResume: int|null

## Testing

```
vendor/bin/phpunit
```

## Notes

* Domain operates on seconds from midnight
* Application layer handles DateTime conversion
* Timezone handling should be done outside the domain
* Current implementation works within a single day
