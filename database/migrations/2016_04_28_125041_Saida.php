<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Saida extends Migration
{
    /**
     * Migrate para a criação de tabela de saida
     *
     * @return void
     */
    public function up()
    {
          Schema::create('saidas', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('produto_id');
                $table->integer('quantidade');
                $table->integer('usuario_id');

                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
                $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');

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
        if(Schema::dropIfExists('saidas')){
            Schema::drop('saidas');
        }
    }
}

