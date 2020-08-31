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
            $table->string("text",255);
            $table->string("audio",100)->nullable();
            $table->integer("user_id");
            $table->integer("group_id")->nullable();
            $table->integer("quest_id")->nullable();
            $table->integer("total_follower")->default(0);
            $table->integer("total_qna")->default(0);
            $table->integer("activity")->default(0);
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
