<?php

namespace Database\Seeders;

use App\Models\Mapping\ParticipantsTable_V1;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantsSeeder extends Seeder
{
    public function run()
    {
        // Main travels participants
        DB::table(ParticipantsTable_V1::TABLE_NAME)->insert([
            ParticipantsTable_V1::COLUMN_TRAVEL_ID => 1,
            ParticipantsTable_V1::COLUMN_USER_ID => 1,
        ]);

        DB::table(ParticipantsTable_V1::TABLE_NAME)->insert([
            ParticipantsTable_V1::COLUMN_TRAVEL_ID => 1,
            ParticipantsTable_V1::COLUMN_USER_ID => 2,
        ]);

        // Additional participants
        \App\Models\Participant::factory(40)->create();
    }
}
