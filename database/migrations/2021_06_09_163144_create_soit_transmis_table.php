<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoitTransmisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soit_transmis', function (Blueprint $table) {
            $table->id();
            $table->string('numero_acte');
            $table->string('commune_destination');
            $table->integer('nombre');
            $table->string('concerne');
            $table->string('conjoint')->nullable();
            $table->string('mention');
            $table->date('date_demande');
            $table->date('date_dresser');
            $table->date('date_mariage')->nullable();
            $table->date('date_deces')->nullable();
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
        Schema::dropIfExists('soit_transmis');
    }
}
