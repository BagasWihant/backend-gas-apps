<?php

namespace App\Http\Controllers\Api\Auth;

use stdClass;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\DynamicProduct;
use App\Models\Marketplace\Category;
use App\Models\Marketplace\UserMarket;
use App\Models\Testproduk;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;

class Register extends Controller
{
    protected $strleft;

    public function __construct()
    {
        $this->strleft= substr(Carbon::now()->timestamp, 3);
    }
    public function register(Request $req)
    {
        // Validasi
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|email:filter,spoof,dns|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }

        // input ke db
        $input  = $req->all();
        $input['password'] = bcrypt($input['password']);
        try {
            DB::beginTransaction();
            $userMain = User::create($input);
            $userMarketId = str_pad($userMain->id, 11, $this->strleft, STR_PAD_RIGHT); //BUAT RANDOM USER ID
            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false, $th->errorInfo[2], $th);
        }

        $res = new stdClass;
        $res->token = $userMain->createToken($userMain->name)->plainTextToken;
        $res->name =  $userMain->name;
        // return response()->json($res,200);
        return new Respons(true, 'Register Succesfully',$res);

    }

    public function registerGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }


    public function handleLoginGoogle()
    {
        try {
            DB::beginTransaction();

            $user = Socialite::driver('google')->stateless()->user();
            $userMain = User::firstOrCreate([
                'email' => $user->email,
                'name' => $user->name,
                'photo' => $user->avatar,
                'password' => 0
            ]);

            $userMarketId = str_pad($userMain->id, 11, $this->strleft, STR_PAD_RIGHT); //BUAT RANDOM USER ID

            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false, $th->errorInfo[2], $th);
        }

        $res = new stdClass;
        $res->name = $userMain->name;
        $res->email = $userMain->email;
        $res->token = $userMain->createToken('token-name')->plainTextToken;
        // return response()->json($res, 200);
        return new Respons(true, 'Register Succesfully',$res);

    }


}
