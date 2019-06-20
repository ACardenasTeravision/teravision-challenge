<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortenedUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortened_urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('code');
            $table->string('shortened_url');
            $table->string('title')->nullable();
            $table->bigInteger('times_visited')->default(0);
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
        Schema::dropIfExists('shortened_urls');
    }
}
