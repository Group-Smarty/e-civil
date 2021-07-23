<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatCelibatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat_celibats', function (Blueprint $table) {
            $table->id();
            $table->string('civilite');
            $table->string('type');
            $table->string('concerne');
            $table->string('lieu_naissance');
            $table->string('numero_act_naissance');
            $table->string('nom_pere')->nullable();
            $table->string('nom_mere')->nullable();
            $table->string('numero_requette')->nullable();
            $table->string('lieu_mariage')->nullable();
            $table->string('conjoint')->nullable();
            $table->string('tribunal')->nullable();
            $table->text('raison_disolution_mariage')->nullable();
            $table->date('date_requette')->nullable();
            $table->date('date_mariage')->nullable();
            $table->date('date_demande');
            $table->date('date_dresser');
            $table->date('date_naissance');
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
        Schema::dropIfExists('certificat_celibats');
    }
}
