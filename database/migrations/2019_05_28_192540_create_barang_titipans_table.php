<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangTitipansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_titipans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('titipan_id');
            $table->string('code_barang');
            $table->string('nama_barang');
            $table->integer('harga_barang');
            $table->integer('jumlah_barang');
            $table->timestamps();
            $table->foreign('titipan_id')->references('id')->on('titipans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_titipans');
    }
}
