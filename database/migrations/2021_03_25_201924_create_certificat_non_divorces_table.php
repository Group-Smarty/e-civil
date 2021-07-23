<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatNonDivorcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_non_divorces', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet_homme');
            $table->string('nom_complet_femme');
            $table->integer('profession_homme');
            $table->integer('profession_femme');
            $table->string('numero_acte_mariage');
            $table->string('etat_civil_mariage');
            $table->date('date_mariage');
            $table->string('pere_homme')->nullable();
            $table->string('mere_homme')->nullable();
            $table->string('mere_femme')->nullable();
            $table->string('pere_femme')->nullable();
            $table->string('numero_acte_naissance')->nullable();
            $table->string('nom_complet_demandeur');
            $table->date('date_demande_certificat');
            $table->string('contact_demandeur')->nullable();
            $table->string('adresse_demandeur')->nullable();
            $table->integer('montant')->unsigned()->default(0);
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
        Schema::dropIfExists('certificat_non_divorces');
    }
}
