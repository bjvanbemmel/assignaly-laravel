<?php

declare(strict_types=1);

namespace App\Enums;

enum AssignmentStatusEnum: string
{
    case OPEN = 'Open';
    case CLOSED = 'Closed';
    case IN_REVIEW = 'In review';
}
