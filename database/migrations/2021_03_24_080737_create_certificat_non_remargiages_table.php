<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatNonRemargiagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_non_remargiages', function (Blueprint $table) {
            $table->id();
            $table->string('interrese');
            $table->string('sexe');
            $table->string('contact_demandeur')->nullable();
            $table->string('adresse_demandeur');
            $table->string('numero_piece_demandeur')->nullable();
            $table->dateTime('date_demande_certificat');
            $table->integer('montant')->unsigned()->default(0);
            $table->string('nom_complet_temoin1');
            $table->string('nom_complet_temoin2')->nullable();
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
        Schema::dropIfExists('certificat_non_remargiages');
    }
}
