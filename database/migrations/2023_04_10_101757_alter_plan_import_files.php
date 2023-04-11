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
        Schema::table('plan_import_files', function (Blueprint $table) {
            $table->boolean('force_import')->default(false)->after('status');
            $table->dropColumn('date_upload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_import_files', function (Blueprint $table) {
            $table->dropColumn('force_import');
            $table->date('date_upload')->useCurrent();
        });
    }
};
