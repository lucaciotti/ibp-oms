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
        Schema::create('plan_import_types_attribute', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_type_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedInteger('cell_num')->nullable();
            $table->timestamps();

            $table->foreign('import_type_id')->references('id')->on('plan_import_types')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('attribute_id')->references('id')->on('attributes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['import_type_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_import_types_attribute');
    }
};
