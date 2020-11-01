<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiamonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamons', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable(); // Jika Null = Topup Admin 
            $table->integer("to_id");
            $table->integer("diamon");
            $table->integer("ref")->nullable(); // Quest id 
            $table->timestamps();

            // Get Diamon saya where (to_id == Auth::id()) = Uang Masuk
            // Get Diamon saya where (user_id == Auth::id()) = Uang Keluar
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diamons');
    }
}
