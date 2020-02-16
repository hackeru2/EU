<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('profiles');
        Schema::create('profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
            // $table->string('name');
            $table->text('experties');
            //$table->string('keywords');
            $table->string('description');
            $table->bigInteger('user_id')->unsigned();
            // $table->string('email')->unique();
            $table->foreign('user_id')
            ->references('id')->on('users');
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
        Schema::dropIfExists('profiles');
    }
}
