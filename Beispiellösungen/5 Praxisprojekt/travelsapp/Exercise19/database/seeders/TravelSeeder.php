<?php

namespace Database\Seeders;

use App\Models\Mapping\TravelsTable_V2 as Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main travels
        DB::table(Table::TABLE_NAME)->insert([
            Table::COLUMN_TITLE => 'Dienstreise nach Hof',
            Table::COLUMN_LOCATION => 'Hochschule Hof',
            Table::COLUMN_START => now()->setHour(10)->setMinute(0)->setSecond(0)->addDays(10),
            Table::COLUMN_END => now()->setHour(11)->setMinute(0)->setSecond(0)->addDays(10),
            Table::COLUMN_AMOUNT => 120.15,
            Table::COLUMN_DETAILS => "Testreise",
            Table::COLUMN_APPLICANT_ID => 1
        ]);

        // Additional travels
        \App\Models\Travel::factory(10)->create();
    }
}
