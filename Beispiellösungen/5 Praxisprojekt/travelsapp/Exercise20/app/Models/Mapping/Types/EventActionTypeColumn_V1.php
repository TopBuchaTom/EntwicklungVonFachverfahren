<?php

namespace App\Models\Mapping\Types;

enum EventActionTypeColumn_V1: string {
    case None = "NONE";
    case Added = "ADDED";
    case Updated = "UPDATED";
    case Deleted = "DELETED";
}

