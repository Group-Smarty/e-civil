<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courriers', function (Blueprint $table) {
            $table->id();
            $table->string('objet');
            $table->dateTime('date_courrier');
            $table->integer('type_courrier_id')->unsigned();
            $table->integer('annuaire_id')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->string('document_scanner')->nullable();
            $table->string('full_nam_particulier')->nullable();
            $table->string('contact_particulier')->nullable();
            $table->text('commentaire')->nullable();
            $table->string('emmettre_recu');
            $table->boolean('particulier')->default(0);
            $table->boolean('traiter')->default(0);
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
        Schema::dropIfExists('courriers');
    }
}
