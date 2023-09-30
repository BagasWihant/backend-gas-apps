<?php

namespace App\Models\Marketplace\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProdukDetail extends Model
{
    use HasFactory;

    protected $primaryKey = ['orderID','sellerID'];
    protected $connection = 'mysql_order';
    protected $guarded = [];
    public $timestamps = false;
}
