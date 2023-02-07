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
        Schema::create('machine_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('matricola');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('cart_id');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->string('impianto');
            $table->string('tipologia');
            $table->string('basamento');
            $table->string('opt_basamento');
            $table->string('colonna');
            $table->string('opt_colonna');
            $table->string('batteria');
            $table->string('ruota_tastatrice');
            $table->string('opt_cart');
            $table->string('opt_cart1');
            $table->string('opt_cart2');
            $table->string('opt_pressore');
            $table->string('opt_rampa_dime');
            $table->string('dim_imballo');
            $table->string('note_imballo');
            $table->string('note');
            $table->string('ral');
            $table->date('data_consegna');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_jobs');
    }
};
