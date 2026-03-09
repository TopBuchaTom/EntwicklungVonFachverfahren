<?php

namespace App\Models;

use App\Models\Mapping\Types\ReviewStateColumn_V1;

enum ReviewState : string
{
    case Pending = ReviewStateColumn_V1::Pending->value;
    case Accepted = ReviewStateColumn_V1::Accepted->value;
    case Declined = ReviewStateColumn_V1::Declined->value;
}
