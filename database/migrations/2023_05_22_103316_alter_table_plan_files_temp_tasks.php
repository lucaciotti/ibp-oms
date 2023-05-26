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
            $table->date('ibp_data_inizio_prod')->comment('Data Inizio Produzione')->after('ibp_data_consegna');
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
            $table->dropColumn('ibp_data_inizio_prod');
        });
    }
};
