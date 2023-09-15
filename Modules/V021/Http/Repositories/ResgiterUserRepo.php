<?php

namespace Modules\V021\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Marketplace\UserMarket;

class ResgiterUserRepo{
    public function register($input){

        try {
            // input ke db
            DB::beginTransaction();
            $userMain = User::create($input);
            $userMarketId = str_pad($userMain->id, 11, $input['time'], STR_PAD_RIGHT); //BUAT RANDOM USER ID
            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);
            // $otp->delete();

            DB::commit();
            $res = collect([
                'number' => $userMain->id,
                'market' => $userMarketId,
                'token' => $userMain->createToken($userMain->name)->plainTextToken,
                'name' => $userMain->name,
            ]);
            return response()->created($res);
            // return response()->json(['message'=>"Berhasil Mendaftar",$res]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>"Terjadi Kesalahan",$th],500);
            // return new Respons(false, $th->errorInfo[2], $th);
        }
    }
}
