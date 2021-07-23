<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatNonNaissancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_non_naissances', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet_enfant');
            $table->string('sexe');
            $table->string('nom_complet_pere')->nullable();
            $table->string('nom_complet_mere')->nullable();
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
        Schema::dropIfExists('certificat_non_naissances');
    }
}
