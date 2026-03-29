<?php
declare(strict_types=1);
namespace BusinessHours\Application\Enum;

enum StatusType: string
{
    case OPEN = 'OPEN';
    case CLOSED = 'CLOSED';
    case ON_BREAK = 'ON_BREAK';
}
