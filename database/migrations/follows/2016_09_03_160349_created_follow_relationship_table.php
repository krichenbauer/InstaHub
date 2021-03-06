<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedFollowRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows' , function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('following_id')->unsigned();;
            $table->integer('follower_id')->unsigned();;
            $table->foreign('following_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('follower_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->timestamps(); // Updated_at and Created_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('follows');
    }
}
