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
        $response = [];
        try {
            // input ke db
            DB::beginTransaction();
            DB::connection('mysql_market')->beginTransaction();

            $userMain = User::create($input);
            $userMarketId = str_pad($userMain->id, 11, $input['time'], STR_PAD_RIGHT); //BUAT RANDOM USER ID
            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);

            DB::commit();
            DB::connection('mysql_market')->commit();

            $res = collect([
                'number' => $userMain->id,
                'market' => $userMarketId,
                'token' => $userMain->createToken($userMain->name)->plainTextToken,
                'name' => $userMain->name,
            ]);
            $response = env('APP_DEBUG') ? response()->created('Berhasil Daftar', $res) : response()->created('Berhasil Daftar');
        } catch (\Throwable $th) {
            DB::rollBack();
            DB::connection('mysql_market')->rollBack();

            $response = env('APP_DEBUG') ? response()->internalServerError($th->getMessage()) : response()->internalServerError();
        }
        return $response;
    }
    public function registerWithGoogle($input)
    {
        $response = [];
        try {
            DB::beginTransaction();
            DB::connection('mysql_market')->beginTransaction();

            $userMain = User::firstOrCreate(
                [
                    'email' => $input['email']
                ],
                [
                    'name' => $input['name'],
                    'photo' => $input['photo'],
                    'password' => 0
                ]
            );

            $userMarketId = str_pad($userMain->id, 11, $input['time'], STR_PAD_RIGHT); //BUAT RANDOM USER ID

            UserMarket::firstOrCreate(
                [
                    'user_id_main' => $userMain->id
                ],
                [
                    'user_id_market' => $userMarketId,
                ]
            );


            $res = [
                'name' => $userMain->name,
                'email' => $userMain->email,
                'token' => $userMain->createToken('token-name')->plainTextToken,
            ];

            $response = env('APP_DEBUG') ? response()->created('Berhasil Masuk Dengan Google', $res) : response()->created('Berhasil Masuk Dengan Google');
            DB::commit();
            DB::connection('mysql_market')->commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            DB::connection('mysql_market')->rollBack();

            $response = env('APP_DEBUG') ? response()->internalServerError($th->getMessage()) : response()->internalServerError();
        }
        return $response;
    }
}
