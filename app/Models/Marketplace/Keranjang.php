<?php

namespace App\Models\Marketplace;

use App\Models\Marketplace\UserMarket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keranjang extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = ['id'];

    public function seller(): HasOne
    {
        return $this->hasOne(UserMarket::class,'user_id_market','seller_id');
    }

}
