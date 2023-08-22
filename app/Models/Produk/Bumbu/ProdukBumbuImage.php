<?php

namespace App\Models\Produk\Bumbu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukBumbuImage extends Model
{
    use HasFactory;

    protected $connection = 'mysql_market';
    protected $guarded = ['id'];
    public $timestamps = false;
}
