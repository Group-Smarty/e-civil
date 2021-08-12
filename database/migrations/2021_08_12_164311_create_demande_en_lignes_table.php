<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandeEnLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demande_en_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_demande');
            $table->string('nom_demandeur');
            $table->string('numero_acte');
            $table->integer('nombre_copie');
            $table->string('type_demande');
            $table->string('contact_demandeur')->nullable();
            $table->date('date_demande');
            $table->boolean('copie_integrale')->nullable()->default(false);
            $table->integer('etat_demande')->default(1); //1 : recu, 2 : en cours, 3 : terminer, 4 : rejeter
            $table->text('motif_rejet')->nullable();
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
        Schema::dropIfExists('demande_en_lignes');
    }
}
