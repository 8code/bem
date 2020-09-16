<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("quest_id")->nullable();
            $table->integer("quest_balas_id")->nullable();
            $table->integer("tipe"); // 0 Melihat, 1 Menyukai, 2 Membalas , 3 Mentions, 4 Mentag, 5 Admin Membuat Quest , 6 Memfollow Akun, 7 Memfollow Group
            $table->string("link")->nullable(); // Link Menuju Quest
            $table->string("mention")->nullable();
            $table->string("tagar")->nullable();
            $table->string("group_id")->nullable();
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
        Schema::dropIfExists('activities');
    }
}
