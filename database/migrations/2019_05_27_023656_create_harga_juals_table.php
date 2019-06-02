<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHargaJualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_juals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produk_id');
            $table->integer('harga_jual');
            $table->timestamps();
            $table->foreign('produk_id')->references('id')->on('produks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('harga_juals');
    }
}
