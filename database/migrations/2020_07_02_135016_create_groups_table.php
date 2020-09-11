<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('username',100)->unique();
            $table->string("name",100);
            $table->string("avatar",100);
            $table->text("desc");
            $table->string("type",50);
            $table->string("last_active",150)->nullable();
            $table->string("university",100)->default('Universitas Siliwangi');
            $table->integer("user_id");
            $table->integer("status")->default(1);
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
        Schema::dropIfExists('groups');
    }
}
