<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Marketplace\UserMarket;
use App\Models\User;

class GantiAkunRepo
{
    public function userToStore($user)
    {
        try {
            $market = UserMarket::where('user_id_main',$user->id)->first();
            if(!$market) return false;

            User::find($user->id)->update(['as_store' => 1]);
            return true;
        } catch (\Throwable $th) {
            return false;
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
