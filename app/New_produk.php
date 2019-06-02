<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, int $int)
 */
class New_produk extends Model
{
    public function getSource()
    {
        return $this->belongsTo(Produk::class,'produk_id');
    }
}
