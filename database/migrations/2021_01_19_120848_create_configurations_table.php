<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('commune');
            $table->string('nom_responsable');
            $table->string('contact_responsable');
            $table->string('service_responsable');
            $table->string('post_responsable');
            $table->string('logo')->nullable();
            $table->string('fax_mairie')->nullable();
            $table->string('telephone_mairie')->nullable();
            $table->string('site_web_mairie')->nullable();
            $table->string('adresse_marie')->nullable();
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
        Schema::dropIfExists('configurations');
    }
}
