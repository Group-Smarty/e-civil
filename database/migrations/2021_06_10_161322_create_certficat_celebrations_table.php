<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertficatCelebrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certficat_celebrations', function (Blueprint $table) {
            $table->id();
            $table->string('numero_acte');
            $table->date('date_dresser');
            $table->string('nom_epoux');
            $table->string('nom_epouse');
            $table->integer('fonction_epouse')->nullable();
            $table->integer('fonction_epoux')->nullable();
            $table->dateTime('date_mariage');
            $table->dateTime('date_demande');
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
        Schema::dropIfExists('certficat_celebrations');
    }
}
