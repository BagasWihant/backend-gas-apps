<?php

namespace App\Models\Otp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otpcode extends Model
{
    use HasFactory;
    protected $connection = 'mysql_market';
    protected $fillable = ['key','otp','valid','tipe','status'];
    public $timestamps = false;
}
