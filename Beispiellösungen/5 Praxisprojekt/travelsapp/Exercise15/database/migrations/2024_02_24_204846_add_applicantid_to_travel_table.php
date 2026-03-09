<?php

use App\Models\Mapping\TravelsTable_V2 as TravelsTable;
use App\Models\Mapping\UsersTable_V2 as UsersTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(TravelsTable::TABLE_NAME, function (Blueprint $table) {
            $table->foreignId(TravelsTable::COLUMN_APPLICANT_ID)->references(UsersTable::COLUMN_ID)->on(UsersTable::TABLE_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TravelsTable::TABLE_NAME);
    }
};
