<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table='produks';

    public function kategori(){
        return $this->belongsTo(Kategori::class,"kategori_id");
    }
}
