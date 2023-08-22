<?php

namespace App\Models\Produk\MakanMinum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukMakanMinumImage extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = ['id'];
    public $timestamps = false;
}
