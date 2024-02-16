<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hqs', function (Blueprint $table) {
            $table->id();
            $table->string('nome',100);
            $table->string('nome_original',100)->nullable();
            $table->unsignedBigInteger('tipo_serie_id');
            $table->string('ano_lancamento',4)->nullable();
            $table->integer('total_edicoes')->nullable();
            $table->string('idioma',100)->nullable();
            $table->text('sinopse')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->text('observacoes')->nullable();
            $table->string('capa', 100)->comment('Capa da HQ')->nullable();
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('tipo_serie_id')->references('id')->on('tipo_series');
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hqs');
    }
}
