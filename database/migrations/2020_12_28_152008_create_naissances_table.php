<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaissancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('naissances', function (Blueprint $table) {
            $table->id();
            //Enfant
            $table->string('prenom_enfant');
            $table->string('nom_enfant');
            $table->string('numero_acte_naissance');
            $table->string('sexe');
            $table->string('lieu_naissance_enfant');
            $table->date('date_naissance_enfant');
            $table->integer('registre');
            $table->date('date_dresser');
            $table->time('heure_naissance_enfant', 0)->nullable();
            
            //Parents
            $table->string('nom_complet_pere')->nullable();
            $table->string('nom_complet_mere')->nullable();
            $table->date('date_naissance_pere')->nullable();
            $table->date('date_naissance_mere')->nullable();
            $table->string('numero_piece_identite_pere')->nullable();
            $table->string('numero_piece_identite_mere')->nullable();
            $table->string('adresse_pere')->nullable();
            $table->string('adresse_mere')->nullable();
            $table->string('lieu_naissance_pere')->nullable();
            $table->string('lieu_naissance_mere')->nullable();
            $table->integer('nationalite_mere')->nullable();
            $table->integer('nationalite_pere')->nullable();
            $table->integer('fonction_pere')->nullable();
            $table->integer('fonction_mere')->nullable();
            $table->string('situation_parents')->nullable();

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
            $table->text('loi')->nullable();
            $table->string('numero_jugement_supletif')->nullable();
            $table->string('tribunale')->nullable();
            $table->date('mention_date_deces')->nullable();
            $table->date('mention_date_divorce')->nullable();
            $table->date('mention_date_mariage')->nullable();
            $table->string('mention_lieu_mariage')->nullable();
            $table->string('mention_lieu_deces')->nullable();
            $table->string('mention_conjoint')->nullable();
            $table->string('nom_temoin_1')->nullable();
            $table->string('nom_temoin_2')->nullable();
            $table->date('date_naissance_temoin_1')->nullable();
            $table->date('date_naissance_temoin_2')->nullable();
            $table->integer('fonction_temoin_1')->unsigned()->nullable();
            $table->integer('fonction_temoin_2')->unsigned()->nullable();
            $table->string('adresse_temoins_1')->nullable();
            $table->string('adresse_temoins_2')->nullable();
            $table->text('dressant')->nullable();
            $table->string('numero_requisition')->nullable();
            $table->text('signataire')->nullable();
            $table->string('langue_reception')->nullable();
            $table->string('traducteur')->nullable();
            $table->date('date_requisition')->nullable();
            
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
        Schema::dropIfExists('naissances');
    }
}
