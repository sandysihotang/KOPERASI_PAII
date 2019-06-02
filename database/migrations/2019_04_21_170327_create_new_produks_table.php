<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_produks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produk_id');
            $table->unsignedInteger('vendor_produk_id');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('produk_id')->references('id')->on('produks');
            $table->foreign('vendor_produk_id')->references('id')->on('vendor_produks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_produks');
    }
}
