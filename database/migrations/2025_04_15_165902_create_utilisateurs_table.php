<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->bigIncrements('id_utilisateur');
            $table->string('email', 255)->unique();
            $table->string('mot_de_passe_hash', 255);
            $table->dateTime('date_creation');
            $table->dateTime('dernier_login')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->unsignedBigInteger('Matricule')->nullable();
            $table->unsignedBigInteger('id_role');
            
            $table->foreign('Matricule')->references('Matricule')->on('personnel')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_role')->references('id_role')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}
