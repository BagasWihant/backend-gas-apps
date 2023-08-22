<?php

namespace App\Models\Produk\BuahSayur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukBuahSayurMain extends Model
{
    use HasFactory;

    protected $connection = 'mysql_market';
    protected $guarded = [];
}
