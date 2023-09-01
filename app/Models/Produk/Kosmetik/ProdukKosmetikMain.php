<?php

namespace App\Models\Produk\Kosmetik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukKosmetikMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];
    public function variasi(): HasMany
    {
        return $this->hasMany(ProdukKosmetikVariasi::class,'produk_id','produk_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProdukKosmetikImage::class,'produk_id','produk_id');
    }
}
