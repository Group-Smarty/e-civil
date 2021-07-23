<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('full_name_agent');
            $table->string('numero_piece_identite')->unique();
            $table->string('situation_matrimoniale');
            $table->string('sexe');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->string('numero_securite')->nullable()->unique();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('adresse');
            $table->integer('service_id')->unsigned();
            $table->integer('fonction_id')->unsigned();
            $table->integer('commune_id')->unsigned();
            $table->integer('type_piece_id')->unsigned();
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
    public function down() {
        Schema::dropIfExists('agents');
    }

}
