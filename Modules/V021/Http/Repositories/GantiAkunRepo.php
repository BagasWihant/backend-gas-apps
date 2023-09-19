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
            if(!$market) return response()->badRequest('Belum Memiliki akun Toko');

            User::find($user->id)->update(['as_store' => 1]);
            return  response()->ok('Berhasil berganti ke '. $market->store_name);
        } catch (\Throwable $th) {
            return env('APP_DEBUG') ? response()->badRequest($th->getMessage()) : response()->badRequest();
        }
    }

    public function storeToUser($user)
    {
        try {
            $user = User::where('id',$user->id)->first();
            $user->as_store = 0;
            $user->save();
            return  response()->ok('Berhasil berganti ke user '. $user->name);
        } catch (\Throwable $th) {
            return env('APP_DEBUG') ? response()->badRequest($th->getMessage()) : response()->badRequest('Gagal berganti akun');
        }
    }
}
