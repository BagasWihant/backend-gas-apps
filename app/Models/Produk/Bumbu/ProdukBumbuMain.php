<?php

namespace App\Models\Produk\Bumbu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukBumbuMain extends Model
{
    use HasFactory;

    protected $connection = 'mysql_market';
    protected $guarded = [];
}
