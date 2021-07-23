<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatVieEntretiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_vie_entretiens', function (Blueprint $table) {
            $table->id();
            $table->string('numero_piece_personne')->nullable();
            $table->string('contact_personne')->nullable();
            $table->string('adresse_personne');
            $table->string('numero_acte_naissance_personne')->nullable();
            $table->string('nom_complet_personne');
            $table->string('lieu_naissance');
            $table->integer('fonction_id')->unsigned()->nullable();
            $table->integer('naissance_id')->unsigned()->nullable();
            $table->dateTime('date_naissance');
            $table->dateTime('date_demande_certificat');
            $table->integer('montant')->unsigned()->default(0);
            $table->boolean('etat_civil_naissance')->default(0);
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
        Schema::dropIfExists('certificat_vie_entretiens');
    }
}
