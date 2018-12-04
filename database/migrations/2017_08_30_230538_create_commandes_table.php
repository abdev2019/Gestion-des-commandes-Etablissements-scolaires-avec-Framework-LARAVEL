<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandesTable extends Migration
{
    
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idUtilisateur');
            $table->integer('idEtablissement');
            $table->integer('idOuvrage');
            $table->integer('quantite');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('commandes');
    }


}
