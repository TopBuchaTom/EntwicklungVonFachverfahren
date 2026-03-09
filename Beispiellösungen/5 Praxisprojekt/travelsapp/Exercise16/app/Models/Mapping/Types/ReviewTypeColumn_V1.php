<?php

namespace App\Models\Mapping\Types;

enum ReviewTypeColumn_V1: string {
    case Request = "REQUEST";
    case Verification = "VERIFICATION";
    case Approval = "APPROVAL";
}

