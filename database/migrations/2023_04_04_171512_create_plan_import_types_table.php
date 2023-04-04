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
        Schema::create('plan_import_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->string('name');
            $table->string('description')->default('');
            $table->boolean('default')->default(false);
            $table->json('columns_import');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('plan_types')
                ->onUpdate('cascade')->onDelete('cascade');            
                
            $table->unique(['type_id', 'name', 'default']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_import_types');
    }
};
