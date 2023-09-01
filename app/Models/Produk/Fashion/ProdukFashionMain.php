<?php

namespace App\Models\Produk\Fashion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produk\Fashion\ProdukFashionImage;
use App\Models\Produk\Fashion\ProdukFashionVariasi;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukFashionMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukFashionVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukFashionImage::class,'produk_id','produk_id');
    }
}
