<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Mapping\UsersTable_V2 as Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Table::TABLE_NAME, function (Blueprint $table) {
            $table->boolean(Table::COLUMN_IS_ADMIN)->default(0);
            $table->boolean(Table::COLUMN_IS_VERIFIER)->default(0);
            $table->boolean(Table::COLUMN_IS_APPROVER)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Table::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(Table::COLUMN_IS_ADMIN);
            $table->dropColumn(Table::COLUMN_IS_VERIFIER);
            $table->dropColumn(Table::COLUMN_IS_APPROVER);
        });
    }
};
