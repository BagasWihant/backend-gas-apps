<?php

namespace App\Models\Produk\Bumbu;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produk\Bumbu\ProdukBumbuImage;
use App\Models\Produk\Bumbu\ProdukBumbuVariasi;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukBumbuMain extends Model
{
    use HasFactory;

    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukBumbuVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukBumbuImage::class,'produk_id','produk_id');
    }
}
