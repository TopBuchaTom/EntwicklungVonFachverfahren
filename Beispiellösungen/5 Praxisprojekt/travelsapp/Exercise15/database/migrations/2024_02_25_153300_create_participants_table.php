<?php

use App\Models\Mapping\ParticipantsTable_V1;
use App\Models\Mapping\TravelsTable_V1;
use App\Models\Mapping\UsersTable_V2;
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
        Schema::create(ParticipantsTable_V1::TABLE_NAME, function (Blueprint $table) {
            $table->bigIncrements(ParticipantsTable_V1::COLUMN_ID);
            $table->foreignId(ParticipantsTable_V1::COLUMN_TRAVEL_ID)->references(TravelsTable_V1::COLUMN_ID)->on(TravelsTable_V1::TABLE_NAME)->onDelete('cascade');
            $table->foreignId(ParticipantsTable_V1::COLUMN_USER_ID)->references(UsersTable_V2::COLUMN_ID)->on(UsersTable_V2::TABLE_NAME)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(ParticipantsTable_V1::TABLE_NAME);
    }
};
