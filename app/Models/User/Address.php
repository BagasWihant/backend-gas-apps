<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    public $timestamps = false;
    protected $guarded=['id'];
}
