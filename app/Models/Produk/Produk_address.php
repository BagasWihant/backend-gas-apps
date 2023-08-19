<?php

namespace App\Models\Produk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk_address extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $timestamps = false;
    protected $guarded = [];
}
