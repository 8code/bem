<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQnasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qnas', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("text",255);
            $table->longText("desc",255)->nullable();
            $table->string("thumb")->nullable(); // Jika Video
            $table->string("embed")->nullable(); // Embed youtube / spotify
            $table->string("img")->nullable(); 
            $table->string("video")->nullable();
            $table->string("audio")->nullable();
            $table->integer("price")->default(0);
            $table->integer("event_id")->nullable();
            $table->integer("group_id")->nullable();
            $table->integer("quest_id")->nullable();
            $table->integer("total_follower")->default(0); // like
            $table->integer("total_qna")->default(0); // balas
            $table->integer("view")->default(0); // dilihat
            $table->integer("activity")->default(0);
            $table->integer("type")->default(0);
            $table->integer("status")->default(1); // 0 Draf, 1 active, 2 Selesai (Donasi,Event)
            $table->boolean("anonim")->default(0);
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
        Schema::dropIfExists('qnas');
    }
}
