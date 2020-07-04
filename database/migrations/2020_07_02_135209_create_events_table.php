<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("name",100);
            $table->string("image",100);
            $table->text("desc");
            $table->string("type",100);
            $table->string("gender")->nullable();
            $table->integer("price");
            $table->datetime("start");
            $table->datetime("end");
            $table->string("location");
            $table->integer("member_max");
            $table->integer("group_id")->nullable();
            $table->integer("user_id");
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
        Schema::dropIfExists('events');
    }
}
