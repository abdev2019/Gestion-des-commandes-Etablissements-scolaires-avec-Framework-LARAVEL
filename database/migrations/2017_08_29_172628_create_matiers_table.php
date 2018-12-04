<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatiersTable extends Migration
{ 
    public function up()
    {
        Schema::create('matiers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->timestamps();
        });
    }
 
    public function down()
    {
        Schema::dropIfExists('matiers');
    }
}
