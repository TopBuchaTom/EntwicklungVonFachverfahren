<?php

namespace Database\Seeders;

use App\Models\Mapping\UsersTable_V1 as Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Main users
        DB::table(Table::TABLE_NAME)->insert([
            Table::COLUMN_NAME => 'root',
            Table::COLUMN_EMAIL => 'root@hof-university.de',
            Table::COLUMN_PASSWORD => Hash::make('test1234'),
        ]);
        DB::table(Table::TABLE_NAME)->insert([
            Table::COLUMN_NAME => 'antragsteller',
            Table::COLUMN_EMAIL => 'antragsteller@hof-university.de',
            Table::COLUMN_PASSWORD => Hash::make('test1234'),
        ]);
        DB::table(Table::TABLE_NAME)->insert([
            Table::COLUMN_NAME => 'mitzeichner',
            Table::COLUMN_EMAIL => 'mitzeichner@hof-university.de',
            Table::COLUMN_PASSWORD => Hash::make('test1234'),
        ]);
        DB::table(Table::TABLE_NAME)->insert([
            Table::COLUMN_NAME => 'genehmiger',
            Table::COLUMN_EMAIL => 'genehmiger@hof-university.de',
            Table::COLUMN_PASSWORD => Hash::make('test1234'),
        ]);

        // Additional users
        \App\Models\User::factory(200)->create();
    }
}
