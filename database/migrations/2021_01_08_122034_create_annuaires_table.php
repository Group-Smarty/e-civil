<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnuairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annuaires', function (Blueprint $table) {
            $table->id();
            $table->string('raison_sociale');
            $table->string('adresse_siege');
            $table->integer('secteur_id')->unsigned();
            $table->integer('type_societe_id')->unsigned();
            $table->string('civilite_personne_contacter');
            $table->string('full_name_personne_contacter');
            $table->string('email')->unique();
            $table->string('contact1');
            $table->string('contact2')->nullable();
            $table->string('post_occupe');
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
        Schema::dropIfExists('annuaires');
    }
}
