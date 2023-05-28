<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\ChangeColumn;
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
        Schema::table('plan_import_types', function (Blueprint $table) {
            $table->boolean('default_export')->default(false)->after('default');
            $table->boolean('use_in_import')->default(true)->after('default');
            $table->boolean('use_in_export')->default(true)->after('default');
            $table->renameColumn('default', 'default_import');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_import_types', function (Blueprint $table) {
            $table->dropColumn(['use_in_import', 'use_in_export', 'default_export']);
            $table->renameColumn('default_import', 'default');
        });
    }
};
