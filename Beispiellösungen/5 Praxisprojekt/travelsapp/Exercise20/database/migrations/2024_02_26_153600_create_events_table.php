<?php

use App\Models\Mapping\EventsTable_V1 as Table;
use App\Models\Mapping\Types\EventActionTypeColumn_V1;
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
            $table->bigIncrements(Table::COLUMN_ID);
            $table->string(Table::COLUMN_ENTITY);
            $table->integer(Table::COLUMN_RECORD_ID);
            $table->integer(Table::COLUMN_PARENT_ID)->nullable();
            $table->integer(Table::COLUMN_ACTION_ID);
            $table->enum(Table::COLUMN_ACTION_TYPE, array_column(EventActionTypeColumn_V1::cases(), "value"));
            $table->text(Table::COLUMN_DATA);
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
