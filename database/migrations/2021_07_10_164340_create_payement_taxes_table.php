<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayementTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payement_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket');
            $table->string('payement_effectuer_par');
            $table->integer('declaration_activite_id')->unsigned();
            $table->integer('caisse_ouverte_id')->unsigned();
            $table->integer('montant')->unsigned();
            $table->dateTime('date_payement');
            $table->dateTime('date_prochain_payement');
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
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
        Schema::dropIfExists('payement_taxes');
    }
}
