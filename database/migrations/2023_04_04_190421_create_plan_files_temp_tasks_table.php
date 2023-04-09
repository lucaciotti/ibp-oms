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
            $table->timestamps();
            $table->boolean('imported')->default(false);
            $table->date('date_last_import')->nullable();
            $table->boolean('selected')->default(false);
            $table->boolean('warning')->default(false);
            $table->text('error')->nullable();
            $table->string('ibp_plan_matricola')->unique()->comment('Matricola');
            $table->date('ibp_data_consegna')->comment('Data Consegna');
            $table->string('ibp_cliente_ragsoc')->comment('Cliente');
            $table->string('ibp_prodotto_tipo')->comment('Tipo Prodotto');
            $table->string('ibp_basamento')->nullable()->default('')->comment('Basamento');
            $table->string('ibp_basamento_opt')->nullable()->default('')->comment('Otp. Basamento');
            $table->string('ibp_impianto')->nullable()->default('')->comment('Impianto');
            $table->string('ibp_braccio')->nullable()->default('')->comment('Braccio');
            $table->string('ibp_colonna')->nullable()->default('')->comment('Colonna');
            $table->string('ibp_colonna_opt')->nullable()->default('')->comment('Opt. Colonna');
            $table->string('ibp_batteria')->nullable()->default('')->comment('Batteria');
            $table->string('ibp_ruota_tastatrice')->nullable()->default('')->comment('Ruota Tastatrice');
            $table->string('ibp_carrello')->nullable()->default('')->comment('Carrello');
            $table->string('ibp_carrello_opt')->nullable()->default('')->comment('Opt. Carrello');
            $table->string('ibp_carrello_opt_2')->nullable()->default('')->comment('Opt.2 Carrello');
            $table->string('ibp_carrello_opt_3')->nullable()->default('')->comment('Opt.3 Carrello');
            $table->string('ibp_pressore_opt')->nullable()->default('')->comment('Opt. Pressore');
            $table->string('ibp_imballo_tipo')->nullable()->default('')->comment('Tipo Imballo');
            $table->string('ibp_imballo_dim')->nullable()->default('')->comment('Dim. Imballo');
            $table->string('ibp_imballo_info')->nullable()->default('')->comment('Info Imballo');
            $table->string('ibp_imballo_note')->nullable()->default('')->comment('Note Imballo');
            $table->string('ibp_rampa_dime_opt')->nullable()->default('')->comment('Opt. Rampa Dime');
            $table->text('ibp_plan_note')->nullable()->comment('Note');
            $table->string('ibp_ral', 10)->nullable()->default('')->comment('RAL');
            $table->integer('ibp_montaggio_time')->unsigned()->nullable()->comment('Tempo Montaggio');
            $table->integer('ibp_imballo_time')->unsigned()->nullable()->comment('Tempo Imballo');
            
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
