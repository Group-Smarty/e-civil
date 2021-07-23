<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('contact');
            $table->string('email')->nullable()->unique();
            $table->string('login')->nullable()->unique();
            $table->string('role');
            $table->string('password');
            $table->string('service')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('confirmation_token')->nullable();
            $table->boolean('chef_service')->default(0);
            $table->boolean('statut_compte')->default(1);
            $table->boolean('etat_user')->default(0);
            $table->integer('employe_id')->unsigned()->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }

}
