<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_demande');
            $table->date('date_demande');
            $table->date('date_retrait_demande');
            $table->string('nom_demandeur');
            $table->string('contact_demandeur')->nullable();
            $table->integer('naissance_id')->nullable();
            $table->integer('mariage_id')->nullable();
            $table->integer('decede_id')->nullable();
            $table->integer('nombre_copie')->unsigned();
            $table->integer('montant')->unsigned()->default(0);
            $table->boolean('copie_integrale')->default(0);
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
        Schema::dropIfExists('demandes');
    }
}
