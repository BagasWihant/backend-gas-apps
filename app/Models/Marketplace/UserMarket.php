<?php

namespace App\Models\Marketplace;

use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserMarket extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $fillable = ['user_id_main','user_id_market'];
    public $timestamps = false;


    public function store(): HasOne
    {
        return $this->hasOne(Store::class,'user_id_market','user_id_market');
    }

}
