<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranskasiProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_produks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produk_id');
            $table->unsignedInteger('transaksi_id');
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('produk_id')->references('id')->on('produks');
            $table->foreign('transaksi_id')->references('id')->on('transaksis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transkasi_produks');
    }
}
