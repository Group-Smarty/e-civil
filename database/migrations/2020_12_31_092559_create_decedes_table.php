<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decedes', function (Blueprint $table) {
            $table->id();
            //Deces
            $table->string('nom_complet_decede');
            $table->string('numero_acte_deces');
            $table->string('sexe');
            $table->date('date_deces');
            $table->time('heure_deces', 0)->nullable();
            $table->integer('registre');
            $table->integer('nationalite')->nullable();
            $table->date('date_dresser');
            $table->date('date_naissance_decede')->nullable();
            $table->string('motif_deces')->nullable();
            $table->string('numero_acte_naissance_decede')->nullable();
            $table->integer('fonction_id')->unsigned()->nullable();
            $table->string('lieu_naissance_decede')->nullable();
            $table->string('lieu_deces')->nullable();
            $table->string('adresse_decede')->nullable();
            
            //Parent
            $table->string('nom_complet_pere')->nullable();
            $table->string('nom_complet_mere')->nullable();
            $table->string('adresse_mere')->nullable();
            $table->string('adresse_pere')->nullable();
            
             //Declarant
            $table->string('nom_complet_declarant');
            $table->date('date_declaration');
            $table->date('date_retrait');
            $table->string('contact_declarant')->nullable();
            $table->string('adresse_declarant')->nullable();
            $table->date('date_naissance_declarant')->nullable();
            $table->integer('fonction_declarant')->nullable();
            $table->integer('nombre_copie')->default(1);
            $table->integer('montant_declaration')->default(0);
            
            //Autres
            $table->string('numero_jugement_supletif')->nullable();
            $table->string('tribunale')->nullable();
            
            $table->string('langue_reception')->nullable();
            $table->string('traducteur')->nullable();
            $table->string('dressant')->nullable();
            $table->string('scanne_pv')->nullable();
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
        Schema::dropIfExists('decedes');
    }
}
