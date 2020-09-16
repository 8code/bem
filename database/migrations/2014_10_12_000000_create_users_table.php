<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateUsersTable extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fb_id',100)->nullable();
            $table->string('username',100)->unique();
            $table->string('name',100);
            $table->string('email')->unique();
            $table->string('avatar',100)->nullable();
            $table->string('password')->nullable();
            $table->string('phone',20)->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender',10)->nullable();
            $table->string('study_program')->nullable();
            $table->integer('angkatan')->nullable();
            $table->string('university')->default('Universitas Siliwangi');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('admin')->default(0);
            $table->string('instagram',100)->nullable();
            $table->string('whatsapp',20)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
