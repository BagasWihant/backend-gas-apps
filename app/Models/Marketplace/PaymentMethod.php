<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $connection = 'mysql_order';
    protected $fillable = ['name','type'];
    public $timestamps = false;
}
