<?php

namespace App\Models\Produk\BuahSayur;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Produk\BuahSayur\ProdukBuahSayurImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Produk\BuahSayur\ProdukBuahSayurVariasi;

class ProdukBuahSayurMain extends Model
{
    use HasFactory;

    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukBuahSayurVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukBuahSayurImage::class,'produk_id','produk_id');
    }
}
