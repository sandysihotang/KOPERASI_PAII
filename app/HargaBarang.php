<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HargaBarang extends Model
{
    protected $table='harga_barangs';
    protected $fillable=['produk_id'];
}
