<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Marketplace\UserMarket;
use App\Models\Store\Store;
use App\Models\User;

class GantiAkunRepo
{
    public function userToStore($user)
    {
        try {
            $market = Store::where('user_id_market',$user->idMarket->user_id_market)->first();
            if(!$market) return [false,'Belum Memiliki akun Toko'];

            User::find($user->id)->update(['as_store' => 1]);
            return [true,'Berhasil berganti ke akun Toko'];
        } catch (\Throwable $th) {
            return [false,'Gagal Ada kesalahan sistem'];
        }
    }

    public function storeToUser($user)
    {
        try {
            User::find($user->id)->update(['as_store' => 0]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
