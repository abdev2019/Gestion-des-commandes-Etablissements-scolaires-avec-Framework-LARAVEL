<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCerclesTable extends Migration
{ 

    public function up()
    {
        Schema::create('cercles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->timestamps();
        });
    }
 

    public function down()
    {
        Schema::dropIfExists('cercles');
    }
}
