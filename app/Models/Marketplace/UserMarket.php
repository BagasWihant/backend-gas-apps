<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMarket extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $fillable = ['user_id_main','user_id_market'];
    public $timestamps = false;

}
