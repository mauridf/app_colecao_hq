<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHqEditorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hq_editoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hq_id');
            $table->unsignedBigInteger('editora_id');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('hq_id')->references('id')->on('hqs');
            $table->foreign('editora_id')->references('id')->on('editoras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hq_editoras');
    }
}
