<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiTitipansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_titipans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaksi_id');
            $table->unsignedInteger('produk_titipan_id');
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('transaksi_id')->references('id')->on('transaksis');
            $table->foreign('produk_titipan_id')->references('id')->on('barang_titipans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_titipans');
    }
}
