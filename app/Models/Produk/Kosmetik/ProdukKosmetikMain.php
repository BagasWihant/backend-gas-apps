<?php

namespace App\Models\Produk\Kosmetik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKosmetikMain extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];
}
