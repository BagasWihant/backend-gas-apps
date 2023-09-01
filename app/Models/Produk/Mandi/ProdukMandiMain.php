<?php

namespace App\Models\Produk\Mandi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukMandiMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukMandiVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukMandiImage::class,'produk_id','produk_id');
    }
}
