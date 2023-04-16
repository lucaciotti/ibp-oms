<?php

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
        Schema::table('plan_files_temp_tasks', function (Blueprint $table) {
            $table->dropUnique('plan_files_temp_tasks_ibp_plan_matricola_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_files_temp_tasks', function (Blueprint $table) {
            $table->unique('ibp_plan_matricola');
        });
    }
};
