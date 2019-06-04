<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangTitipan extends Model
{
    protected $table='barang_titipans';
    public function allPenjualanTitipan(){
        return $this->hasMany(TransaksiTitipan::class,'produk_titipan_id');
    }
}
