<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDetail extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $guarded = [];
    public $timestamps = false;
}
