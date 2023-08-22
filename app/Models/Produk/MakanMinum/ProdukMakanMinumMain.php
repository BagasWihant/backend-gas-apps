<?php

namespace App\Models\Produk\MakanMinum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukMakanMinumMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];
}
