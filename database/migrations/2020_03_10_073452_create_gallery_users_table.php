<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('gallery_id')->index()->unsigned();
            $table->bigInteger('user_id')->index()->unsigned();
            $table->foreign('gallery_id')->on('galleries')->references('id')->onDelete('cascade');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('gallery_users');
    }
}
