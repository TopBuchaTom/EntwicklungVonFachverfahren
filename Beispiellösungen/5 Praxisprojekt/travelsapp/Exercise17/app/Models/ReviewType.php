<?php

namespace App\Models;

use App\Models\Mapping\Types\ReviewTypeColumn_V1;

enum ReviewType : string
{
    case Request = ReviewTypeColumn_V1::Request->value;
    case Verification = ReviewTypeColumn_V1::Verification->value;
    case Approval = ReviewTypeColumn_V1::Approval->value;
}
