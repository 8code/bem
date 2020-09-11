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
            $table->integer("quest_user_id");
            $table->integer("quest_group_id")->nullable(); // Khusus Info dari Admin Group
            $table->integer("tipe"); // 1 Menyukai Quest, 2 Membalas Quest , 3 Admin Membuat Quest , 4 Memfollow Akun
            $table->string("activity"); // Text "@admin Membuat Quest 'Ini Peryaratan Buat Ombus Fakultas Ya' "
            $table->string("link"); // Link Menuju Quest
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
