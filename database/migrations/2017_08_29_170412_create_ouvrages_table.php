<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOuvragesTable extends Migration
{ 

    public function up()
    {
        Schema::create('ouvrages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('niveauScolaire');
            $table->integer('niveau');
            $table->string('code');
            $table->integer('idMatier');
            $table->string('designation');
            $table->string('unite');
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('ouvrages');
    }
}
