<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclarationActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declaration_activites', function (Blueprint $table) {
            $table->id();
            $table->string('nom_activite');
            $table->string('nom_structure');
            $table->string('numero_cc');
            $table->string('numero_registre');
            $table->bigInteger('montant_taxe');
            $table->string('contact');
            $table->date('date_declaration');
            $table->string('situation_geographique');
            $table->integer('contribuable_id')->unsigned();
            $table->integer('type_societe_id')->unsigned();
            $table->integer('secteur_id')->unsigned();
            $table->integer('type_taxe_id')->unsigned();
            $table->integer('localite_id')->unsigned();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->string('adresse_postale')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('declaration_activites');
    }
}
