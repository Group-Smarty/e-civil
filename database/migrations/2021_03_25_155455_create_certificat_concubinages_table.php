<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatConcubinagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_concubinages', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet_homme');
            $table->string('nom_complet_femme');
            $table->integer('profession_homme');
            $table->integer('profession_femme');
            $table->string('adresse_homme');
            $table->string('adresse_femme');
            $table->date('date_naissance_homme');
            $table->date('date_naissance_femme');
            $table->date('date_mariage_coutumier');
            $table->string('lieu_mariage_coutumier');
            $table->string('adresse_commune');
            $table->string('nom_complet_temoins_1')->nullable();
            $table->string('nom_complet_temoins_2')->nullable();
            $table->integer('profession_temoins_1')->nullable();
            $table->integer('profession_temoins_2')->nullable();
            $table->string('adresse_temoins_1')->nullable();
            $table->string('adresse_temoins_2')->nullable();
            $table->string('numero_piece_temoins_1')->nullable();
            $table->string('numero_piece_temoins_2')->nullable();
            $table->date('date_etablisssement_piece_temoins_1')->nullable();
            $table->date('date_etablisssement_piece_temoins_2')->nullable();
            $table->string('lieu_etablisssement_piece_temoins_1')->nullable();
            $table->string('lieu_etablisssement_piece_temoins_2')->nullable();
            $table->string('piece_temoins_1_delivre_par')->nullable();
            $table->string('piece_temoins_2_delivre_par')->nullable();
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
        Schema::dropIfExists('certificat_concubinages');
    }
}
