<?php

use App\Models\Mapping\TravelsTable_V1 as Table;
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
        Schema::create(Table::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(Table::COLUMN_TITLE);
            $table->string(Table::COLUMN_LOCATION);
            $table->dateTime(Table::COLUMN_START);
            $table->dateTime(Table::COLUMN_END);
            $table->decimal(Table::COLUMN_AMOUNT);
            $table->string(Table::COLUMN_DETAILS)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Table::TABLE_NAME);
    }
};
