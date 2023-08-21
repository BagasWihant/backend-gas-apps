<?php

namespace Modules\V021\Http\Repositories;

use App\Models\User;

class GantiAkunRepo
{
    public function userToStore($user)
    {
        try {
            User::find($user->id)->update(['as_store' => 1]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function storeToUser($user)
    {
        try {
            User::find($user->id)->update(['as_store' => null]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
