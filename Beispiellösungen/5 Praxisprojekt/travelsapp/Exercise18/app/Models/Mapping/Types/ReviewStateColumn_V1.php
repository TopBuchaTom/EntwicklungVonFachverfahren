<?php

namespace App\Models\Mapping\Types;

enum ReviewStateColumn_V1: string {
    case Pending = "PENDING";
    case Accepted = "ACCEPTED";
    case Declined = "DECLINED";
}

