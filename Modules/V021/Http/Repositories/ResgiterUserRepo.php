<?php

namespace Modules\V021\Http\Repositories;

use stdClass;
use App\Models\User;
use App\Http\Resources\Respons;
use Illuminate\Support\Facades\DB;
use App\Models\Marketplace\UserMarket;

class ResgiterUserRepo
{
    public function register($input)
    {

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
            return response()->json(['message' => "Terjadi Kesalahan", $th], 500);
            // return new Respons(false, $th->errorInfo[2], $th);
        }
    }
    public function registerWithGoogle($input)
    {

        try {
            DB::beginTransaction();

            $userMain = User::firstOrCreate([
                'email' => $input['email'],
                'name' => $input['name'],
                'photo' => $input['photo'],
                'password' => 0
            ]);

            $userMarketId = str_pad($userMain->id, 11, $input['time'], STR_PAD_RIGHT); //BUAT RANDOM USER ID

            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);

            DB::commit();

            $res = new stdClass;
            $res->name = $userMain->name;
            $res->email = $userMain->email;
            $res->token = $userMain->createToken('token-name')->plainTextToken;
            // return response()->json($res, 200);
            return new Respons(true, 'Register Succesfully', $res);
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false, $th->errorInfo[2], $th);
        }
    }
}
