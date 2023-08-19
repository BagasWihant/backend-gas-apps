<?php

namespace App\Models\Produk\Fashion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk_fashion_image extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $timestamps = false;
    protected $guarded = ['id'];
}