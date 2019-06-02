<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_produks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_vendor')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('no_telepon')->nullable();
            $table->boolean('status_data')->default(0);
            $table->string('excel_file')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_produks');
    }
}
