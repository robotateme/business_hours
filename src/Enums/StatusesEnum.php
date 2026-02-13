<?php

namespace BusinessHours\Enums;

enum StatusesEnum: string
{
    case STATUS_OPEN = 'open';
    case STATUS_CLOSED = 'closed';
    case STATUS_BREAK = 'brake';
}
