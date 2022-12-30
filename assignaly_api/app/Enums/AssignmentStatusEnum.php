<?php

declare(strict_types=1);

namespace App\Enums;

enum AssignmentStatusEnum: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case IN_REVIEW = 'in-review';
}
