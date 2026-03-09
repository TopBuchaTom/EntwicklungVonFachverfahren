<?php

namespace App\Models\Mapping;

class UsersTable_V2 extends UsersTable_V1 {
    const COLUMN_IS_ADMIN = "is_admin";
    const COLUMN_IS_VERIFIER = "is_verifier";
    const COLUMN_IS_APPROVER = "is_approver";
}
