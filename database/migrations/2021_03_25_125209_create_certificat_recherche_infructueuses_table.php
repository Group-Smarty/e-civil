<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatRechercheInfructueusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_recherche_infructueuses', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet_concerne');
            $table->string('nom_complet_demandeur');
            $table->string('numero_certificat_medical');
            $table->string('lieu_certificat_medical');
            $table->string('lieu_evenement');
            $table->date('date_certificat_medical');
            $table->date('date_evenement');
            $table->string('adresse_demandeur');
            $table->date('date_demande_certificat');
            $table->string('contact_demandeur')->nullable();
            $table->string('numero_piece_demandeur')->nullable();
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
        Schema::dropIfExists('certificat_recherche_infructueuses');
    }
}
