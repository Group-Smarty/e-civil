<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContribuablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribuables', function (Blueprint $table) {
            $table->id();
            $table->string('numero_identifiant');
            $table->string('nom_complet');
            $table->string('sexe');
            $table->string('contact');
            $table->string('numero_piece');
            $table->string('situation_matrimoniale');
            $table->integer('commune_id')->unsigned();
            $table->integer('type_piece_id')->unsigned();
            $table->integer('nation_id')->unsigned();
            $table->integer('fonction_id')->nullable()->unsigned();
            $table->date('date_naissance');
            $table->string('contact2')->nullable();
            $table->string('adresse')->nullable();
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
        Schema::dropIfExists('contribuables');
    }
}
