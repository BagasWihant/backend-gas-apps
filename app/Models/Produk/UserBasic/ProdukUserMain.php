<?php

namespace App\Models\Produk\UserBasic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukUserMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukUserVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukUserImage::class,'produk_id','produk_id');
    }
}
