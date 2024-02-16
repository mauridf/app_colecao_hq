<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHqPersonagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hq_personagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hq_id');
            $table->unsignedBigInteger('personagem_id');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('hq_id')->references('id')->on('hqs');
            $table->foreign('personagem_id')->references('id')->on('personagens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hq_personagens');
    }
}
