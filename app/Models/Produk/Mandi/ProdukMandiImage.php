<?php

namespace App\Models\Produk\Mandi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukMandiImage extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = ['id'];
    public $timestamps = false;
}
