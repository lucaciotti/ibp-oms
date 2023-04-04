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
        Schema::create('plan_files_temp_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_file_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('plan_matricola');
            $table->date('data_consegna');
            $table->string('cliente_ragsoc');
            $table->string('prodotto_tipo');
            $table->string('basamento')->default('');
            $table->string('basamento_opt')->default('');
            $table->string('impianto')->default('');
            $table->string('braccio')->default('');
            $table->string('colonna')->default('');
            $table->string('colonna_opt')->default('');
            $table->string('batteria')->default('');
            $table->string('ruota_tastatrice')->default('');
            $table->string('carrello')->default('');
            $table->string('carrello_opt')->default('');
            $table->string('carrello_opt_2')->default('');
            $table->string('carrello_opt_3')->default('');
            $table->string('pressore_opt')->default('');
            $table->string('imballo_tipo')->default('');
            $table->string('imballo_dim')->default('');
            $table->string('imballo_info')->default('');
            $table->string('imballo_note')->default('');
            $table->string('rampa_dime_opt')->default('');
            $table->text('plan_note')->nullable();
            $table->string('ral',10)->default('');
            $table->integer('montaggio_time')->unsigned()->nullable()->default(12);
            $table->integer('imballo_time')->unsigned()->nullable()->default(12);
            $table->boolean('imported')->default(false);
            $table->date('date_last_import')->nullable();
            $table->boolean('selected')->default(false);
            $table->boolean('warning')->default(false);
            $table->text('error')->nullable();
            $table->timestamps();
            
            $table->foreign('import_file_id')->references('id')->on('plan_import_files')
                ->onUpdate('cascade')->onDelete('cascade');   
                
            $table->foreign('type_id')->references('id')->on('plan_types')
                ->onUpdate('cascade')->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_files_temp_tasks');
    }
};
