<?php

namespace App\Models\Produk\UserBasic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukUserMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];
}
