<?php

namespace App\Models\Produk\KebutuhanPokok;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKebutuhanPokokImage extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded =['id'];
    public $timestamps = false;

}
