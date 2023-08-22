<?php

namespace App\Models\Produk\Mandi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukMandiMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];
}
