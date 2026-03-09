<?php

use App\Models\Mapping\ReviewsTable_V1;
use App\Models\Mapping\TravelsTable_V1;
use App\Models\Mapping\Types\ReviewStateColumn_V1;
use App\Models\Mapping\Types\ReviewTypeColumn_V1;
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
        Schema::create(ReviewsTable_V1::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId(ReviewsTable_V1::COLUMN_TRAVEL_ID)->references(TravelsTable_V1::COLUMN_ID)->on(TravelsTable_V1::TABLE_NAME);
            $table->foreignId(ReviewsTable_V1::COLUMN_USER_ID)->references(UsersTable_V2::COLUMN_ID)->on(UsersTable_V2::TABLE_NAME);
            $table->enum(ReviewsTable_V1::COLUMN_TYPE, array_column(ReviewTypeColumn_V1::cases(), "value"));
            $table->enum(ReviewsTable_V1::COLUMN_STATE, array_column(ReviewStateColumn_V1::cases(), "value"));
            $table->boolean(ReviewsTable_V1::COLUMN_CHANGED)->default(0);
            $table->string(ReviewsTable_V1::COLUMN_COMMENT)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(ReviewsTable_V1::TABLE_NAME);
    }
};
