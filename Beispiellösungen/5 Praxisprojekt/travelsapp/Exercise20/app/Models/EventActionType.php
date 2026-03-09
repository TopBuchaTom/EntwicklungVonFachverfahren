<?php

namespace App\Models;

use App\Models\Mapping\Types\EventActionTypeColumn_V1;

enum EventActionType : string
{
    case None = EventActionTypeColumn_V1::None->value;
    case Added = EventActionTypeColumn_V1::Added->value;
    case Updated = EventActionTypeColumn_V1::Updated->value;
    case Deleted = EventActionTypeColumn_V1::Deleted->value;
}
