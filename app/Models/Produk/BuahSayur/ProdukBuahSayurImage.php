<?php

namespace App\Models\Produk\BuahSayur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukBuahSayurImage extends Model
{
    use HasFactory;

    protected $connection = 'mysql_market';
    protected $guarded = ['id'];
    public $timestamps = false;
}
