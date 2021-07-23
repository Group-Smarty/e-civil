<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMariagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mariages', function (Blueprint $table) {
            $table->id();
            
            //Mariage
            $table->string('numero_acte_mariage');
            $table->date('date_dresser');
            $table->integer('registre');
            $table->dateTime('date_mariage');
            $table->integer('regime_id')->unsigned()->nullable();
            
            //Epoux
            $table->string('nom_complet_homme');
            $table->string('adresse_domicile_homme')->nullable();
            $table->integer('fonction_homme')->nullable();
            $table->string('numero_acte_naissance_homme')->nullable();
            $table->date('date_naissance_homme')->nullable();
            $table->string('lieu_naissance_homme')->nullable();
            $table->text('decret_autorisation_homme')->nullable();

            //Epouse
            $table->string('nom_complet_femme');
            $table->string('adresse_domicile_femme')->nullable();
            $table->integer('fonction_femme')->nullable();
            $table->string('numero_acte_naissance_femme')->nullable();
            $table->date('date_naissance_femme')->nullable();
            $table->string('lieu_naissance_femme')->nullable();
            $table->text('decret_autorisation_femme')->nullable();
                        
            //Parent epoux
            $table->string('nom_complet_pere_homme')->nullable();
            $table->string('nom_complet_mere_homme')->nullable();
            $table->string('adresse_mere_homme')->nullable();
            $table->string('adresse_pere_homme')->nullable();
            
            //Parent epouse
            $table->string('nom_complet_pere_femme')->nullable();
            $table->string('nom_complet_mere_femme')->nullable();
            $table->string('adresse_mere_femme')->nullable();
            $table->string('adresse_pere_femme')->nullable();
            
            //Déclarant 
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
            $table->text('dressant')->nullable();
            $table->string('langue_reception')->nullable();
            $table->string('traducteur')->nullable();
            $table->string('nom_complet_temoin_1')->nullable();
            $table->string('nom_complet_temoin_2')->nullable();
            $table->string('adresse_temoin_1')->nullable();
            $table->string('adresse_temoin_2')->nullable();
            $table->integer('fonction_temoin_1')->unsigned()->nullable();
            $table->integer('fonction_temoin_2')->unsigned()->nullable();
            $table->text('signataire')->nullable();
            
            //En attendant
            $table->text('mention_1')->nullable();
            $table->text('mention_2')->nullable();
            $table->text('mention_3')->nullable();
            $table->text('mention_4')->nullable();
            $table->text('mention_5')->nullable();
            $table->text('mention_6')->nullable();
            $table->text('mention_7')->nullable();
            $table->text('mention_8')->nullable();
           
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
        Schema::dropIfExists('mariages');
    }
}
