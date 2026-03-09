<?php

namespace Database\Seeders;

use App\Models\Mapping\ReviewsTable_V1;
use App\Models\Mapping\Types\ReviewStateColumn_V1;
use App\Models\Mapping\Types\ReviewTypeColumn_V1;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        // Main travels reviews
        DB::table(ReviewsTable_V1::TABLE_NAME)->insert([
            ReviewsTable_V1::COLUMN_TRAVEL_ID => 1,
            ReviewsTable_V1::COLUMN_USER_ID => 2,
            ReviewsTable_V1::COLUMN_TYPE => ReviewTypeColumn_V1::Request->value,
            ReviewsTable_V1::COLUMN_STATE => ReviewStateColumn_V1::Accepted->value,
            ReviewsTable_V1::COLUMN_CHANGED => 0,
        ]);
        DB::table(ReviewsTable_V1::TABLE_NAME)->insert([
            ReviewsTable_V1::COLUMN_TRAVEL_ID => 1,
            ReviewsTable_V1::COLUMN_USER_ID => 3,
            ReviewsTable_V1::COLUMN_TYPE => ReviewTypeColumn_V1::Verification->value,
            ReviewsTable_V1::COLUMN_STATE => ReviewStateColumn_V1::Declined->value,
            ReviewsTable_V1::COLUMN_CHANGED => 1,
        ]);
        DB::table(ReviewsTable_V1::TABLE_NAME)->insert([
            ReviewsTable_V1::COLUMN_TRAVEL_ID => 1,
            ReviewsTable_V1::COLUMN_USER_ID => 4,
            ReviewsTable_V1::COLUMN_TYPE => ReviewTypeColumn_V1::Approval->value,
            ReviewsTable_V1::COLUMN_STATE => ReviewStateColumn_V1::Accepted->value,
            ReviewsTable_V1::COLUMN_CHANGED => 1,
        ]);

        // Additional travels reviews
        \App\Models\Review::factory(100)->create();
    }
}
