<?php

namespace App\Models\Produk\MakanMinum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukMakanMinumMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];

    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukMakanMinumVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukMakanMinumImage::class,'produk_id','produk_id');
    }
}
