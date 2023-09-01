<?php

namespace App\Models\Produk\KebutuhanPokok;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukKebutuhanPokokMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukKebutuhanPokokVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukKebutuhanPokokImage::class,'produk_id','produk_id');
    }
}
