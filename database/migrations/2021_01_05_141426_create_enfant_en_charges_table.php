<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnfantEnChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enfant_en_charges', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet_enfant');
            $table->string('numero_extrait_enfant');
            $table->string('lieu_naissance_enfant');
            $table->integer('certificat_vie_entretien_id')->unsigned();
            $table->dateTime('date_naissance');
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
        Schema::dropIfExists('enfant_en_charges');
    }
}
