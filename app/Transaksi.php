<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table='transaksis';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
