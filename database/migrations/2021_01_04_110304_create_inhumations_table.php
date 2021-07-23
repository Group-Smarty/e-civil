<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhumationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhumations', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet_demandeur');
            $table->string('contact_demandeur')->nullable();
            $table->string('adresse_demandeur');
            $table->string('numero_piece_demandeur')->nullable();
            $table->string('nom_complet_defunt');
            $table->string('adresse_defunt');
            $table->string('lieu_deces');
            $table->string('lieu_inhumation');
            $table->string('nom_complet_medecin')->nullable();
            $table->string('scanne_pv_ou_certificat_deces');
            $table->string('numero_piece_defunt')->nullable();
            $table->string('numero_acte_naissance_defunt')->nullable();
            $table->string('lieu_obseque')->nullable();
            $table->integer('montant')->unsigned()->default(0);
            $table->integer('deces_id')->unsigned()->nullable();
            $table->integer('fonction_id')->unsigned()->nullable();
            $table->dateTime('date_deces');
            $table->dateTime('date_inhumation');
            $table->dateTime('date_demande_permis');
            $table->dateTime('date_obseque')->nullable();
            $table->boolean('inhumer_chez_lui')->default(0);
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
        Schema::dropIfExists('inhumations');
    }
}
