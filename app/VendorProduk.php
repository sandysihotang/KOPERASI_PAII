<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorProduk extends Model
{
    protected $table='vendor_produks';

    public function produk(){
        return $this->hasMany(New_produk::class,'vendor_produk_id');
    }

    public function penerima(){
        return $this->belongsTo(User::class,'user_id');
    }
}
